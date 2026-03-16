<?php

namespace App\Exports;

use App\Models\UserSubscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SubscriptionsExport implements
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
        $query = UserSubscription::with(['user', 'membershipPlan', 'location'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }
        if (!empty($this->filters['location_id'])) {
            $query->where('location_id', $this->filters['location_id']);
        }
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $rows = $query->get()->map(fn($s) => [
            'ID'             => '#' . $s->id,
            'Customer'       => $s->user->name ?? '-',
            'Phone'          => $s->user->phone ?? '-',
            'Email'          => $s->user->email ?? '-',
            'Plan'           => $s->membershipPlan->name ?? '-',
            'Location'       => $s->location
                                    ? $s->location->name . ($s->location->area ? ' - ' . $s->location->area : '')
                                    : '-',
            'Start Date'     => $s->start_date->format('d M Y'),
            'End Date'       => $s->end_date->format('d M Y'),
            'Status'         => ucfirst($s->status),
            'Payment Status' => ucfirst($s->payment_status),
            'Amount (₹)'     => number_format($s->amount_paid ?? $s->membershipPlan->price, 2),
            'Transaction ID' => $s->transaction_id ?? '-',
        ]);

        $this->rowCount = $rows->count();
        return $rows;
    }

    public function headings(): array
    {
        return ['ID', 'Customer', 'Phone', 'Email', 'Plan', 'Location', 'Start Date', 'End Date', 'Status', 'Payment Status', 'Amount (₹)', 'Transaction ID'];
    }

    public function title(): string
    {
        return 'Subscriptions ' . now()->format('d-M-Y');
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 22, 'C' => 15, 'D' => 28, 'E' => 22, 'F' => 22, 'G' => 14, 'H' => 14, 'I' => 13, 'J' => 16, 'K' => 14, 'L' => 28];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = 'L';

        // Header row
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2F4A1E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1A2E0F']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Data rows
        for ($row = 2; $row <= $lastRow; $row++) {
            $status = strtolower((string) $sheet->getCell("I{$row}")->getValue());
            $rowBg  = match ($status) {
                'active'    => 'FFD1FAE5',
                'pending'   => 'FFFEF9C3',
                'paused'    => 'FFDBEAFE',
                'cancelled' => 'FFFEE2E2',
                'expired'   => 'FFF3F4F6',
                default     => 'FFFFFFFF',
            };
            $textColor = match ($status) {
                'active'    => 'FF065F46',
                'pending'   => 'FF92400E',
                'paused'    => 'FF1E40AF',
                'cancelled' => 'FF991B1B',
                'expired'   => 'FF374151',
                default     => 'FF111827',
            };

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("I{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $textColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // Outer border
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF2F4A1E']]],
        ]);

        $sheet->freezePane('A2');
        return [];
    }
}
