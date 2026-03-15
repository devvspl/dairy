<?php

namespace App\Exports;

use App\Models\DeliveryLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TodayDeliveriesExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected string $status;
    public int $rowCount = 0;

    public function __construct(string $status = '')
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = DeliveryLog::with(['subscription.user', 'subscription.membershipPlan'])
            ->today()
            ->orderBy('status');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $rows = $query->get()->map(fn ($d) => [
            'Customer'     => $d->subscription->user->name ?? '-',
            'Phone'        => $d->subscription->user->phone ?? '-',
            'Plan'         => $d->subscription->membershipPlan->name ?? '-',
            'Quantity (L)' => $d->quantity_delivered,
            'Status'       => ucfirst($d->status),
            'Time'         => $d->delivery_time
                                ? \Carbon\Carbon::parse($d->delivery_time)->format('h:i A')
                                : '-',
            'Notes'        => $d->notes ?? '-',
        ]);

        $this->rowCount = $rows->count();
        return $rows;
    }

    public function headings(): array
    {
        return ['Customer', 'Phone', 'Plan', 'Quantity (L)', 'Status', 'Time', 'Notes'];
    }

    public function title(): string
    {
        return 'Deliveries ' . now()->format('d-M-Y');
    }

    public function columnWidths(): array
    {
        return ['A' => 25, 'B' => 16, 'C' => 22, 'D' => 14, 'E' => 12, 'F' => 12, 'G' => 30];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2F4A1E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1A2E0F']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Data rows
        for ($row = 2; $row <= $lastRow; $row++) {
            $status = $sheet->getCell("E{$row}")->getValue();
            $rowBg  = match (strtolower((string) $status)) {
                'delivered' => 'FFD1FAE5',
                'pending'   => 'FFFEF9C3',
                'skipped'   => 'FFF3F4F6',
                'failed'    => 'FFFEE2E2',
                default     => 'FFFFFFFF',
            };
            $textColor = match (strtolower((string) $status)) {
                'delivered' => 'FF065F46',
                'pending'   => 'FF92400E',
                'skipped'   => 'FF374151',
                'failed'    => 'FF991B1B',
                default     => 'FF111827',
            };

            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("E{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $textColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // Outer border
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF2F4A1E']]],
        ]);

        $sheet->freezePane('A2');
        return [];
    }
}
