<?php

namespace App\Exports;

use App\Models\ProductOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductOrdersExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected array $filters;
    public int $rowCount = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = ProductOrder::orderBy('created_at', 'desc');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['product_id'])) {
            $query->where('items', 'like', '%"id":' . (int) $this->filters['product_id'] . '%');
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(fn($q) => $q
                ->where('customer_name',  'like', "%{$s}%")
                ->orWhere('customer_phone', 'like', "%{$s}%")
                ->orWhere('customer_email', 'like', "%{$s}%")
                ->orWhere('order_id',       'like', "%{$s}%")
            );
        }

        $rows = $query->get()->map(fn($o) => [
            'Order ID'       => $o->order_id,
            'Customer'       => $o->customer_name,
            'Phone'          => $o->customer_phone,
            'Email'          => $o->customer_email ?? '-',
            'Items'          => collect($o->items)->sum('quantity'),
            'Amount (₹)'     => number_format($o->amount, 2),
            'Discount (₹)'   => $o->discount_amount > 0 ? number_format($o->discount_amount, 2) : '-',
            'Coupon'         => $o->coupon_code ?? '-',
            'Payment Method' => ucfirst(str_replace('_', ' ', $o->payment_method ?? '-')),
            'Status'         => ucfirst($o->status),
            'Transaction ID' => $o->transaction_id ?? '-',
            'Paid At'        => $o->paid_at ? $o->paid_at->format('d M Y H:i') : '-',
            'Order Date'     => $o->created_at->format('d M Y H:i'),
        ]);

        $this->rowCount = $rows->count();
        return $rows;
    }

    public function headings(): array
    {
        return ['Order ID', 'Customer', 'Phone', 'Email', 'Items', 'Amount (₹)', 'Discount (₹)', 'Coupon', 'Payment Method', 'Status', 'Transaction ID', 'Paid At', 'Order Date'];
    }

    public function title(): string
    {
        return 'Product Orders ' . now()->format('d-M-Y');
    }

    public function columnWidths(): array
    {
        return ['A' => 22, 'B' => 22, 'C' => 15, 'D' => 28, 'E' => 8, 'F' => 14, 'G' => 14, 'H' => 16, 'I' => 18, 'J' => 13, 'K' => 28, 'L' => 18, 'M' => 18];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = 'M';

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2F4A1E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1A2E0F']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        for ($row = 2; $row <= $lastRow; $row++) {
            $status = strtolower((string) $sheet->getCell("J{$row}")->getValue());
            $rowBg  = match ($status) {
                'success'   => 'FFD1FAE5',
                'pending'   => 'FFFEF9C3',
                'failed'    => 'FFFEE2E2',
                'cancelled' => 'FFF3F4F6',
                default     => 'FFFFFFFF',
            };
            $textColor = match ($status) {
                'success'   => 'FF065F46',
                'pending'   => 'FF92400E',
                'failed'    => 'FF991B1B',
                'cancelled' => 'FF374151',
                default     => 'FF111827',
            };

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("J{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $textColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF2F4A1E']]],
        ]);

        $sheet->freezePane('A2');
        return [];
    }
}
