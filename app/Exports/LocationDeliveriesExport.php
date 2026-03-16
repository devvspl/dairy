<?php

namespace App\Exports;

use App\Models\DeliveryLog;
use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LocationDeliveriesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public int $rowCount = 0;

    public function __construct(
        protected Location $location,
        protected string   $date,
        protected string   $status = ''
    ) {}

    public function collection()
    {
        $query = DeliveryLog::with(['subscription.user', 'subscription.membershipPlan'])
            ->whereHas('subscription', fn($q) => $q->where('location_id', $this->location->id))
            ->whereDate('delivery_date', $this->date)
            ->orderBy('status');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $rows = $query->get()->map(fn($d) => [
            'Customer'    => $d->subscription->user->name ?? '-',
            'Phone'       => $d->subscription->user->phone ?? '-',
            'Address'     => $d->subscription->delivery_address ?? '-',
            'Plan'        => $d->subscription->membershipPlan->name ?? '-',
            'Qty (L)'     => $d->quantity_delivered,
            'Status'      => ucfirst($d->status),
            'Time'        => $d->delivery_time
                                ? \Carbon\Carbon::parse($d->delivery_time)->format('h:i A')
                                : '-',
            'Notes'       => $d->notes ?? '-',
        ]);

        $this->rowCount = $rows->count();
        return $rows;
    }

    public function headings(): array
    {
        return ['Customer', 'Phone', 'Address', 'Plan', 'Qty (L)', 'Status', 'Time', 'Notes'];
    }

    public function title(): string
    {
        return $this->location->name . ' ' . \Carbon\Carbon::parse($this->date)->format('d-M-Y');
    }

    public function columnWidths(): array
    {
        return ['A' => 24, 'B' => 15, 'C' => 32, 'D' => 22, 'E' => 10, 'F' => 12, 'G' => 12, 'H' => 28];
    }

    public function styles(Worksheet $sheet)
    {
        $last = $sheet->getHighestRow();

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2F4A1E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1A2E0F']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        for ($row = 2; $row <= $last; $row++) {
            $status = strtolower((string) $sheet->getCell("F{$row}")->getValue());
            $bg = match ($status) {
                'delivered' => 'FFD1FAE5',
                'pending'   => 'FFFEF9C3',
                'skipped'   => 'FFF3F4F6',
                'failed'    => 'FFFEE2E2',
                default     => 'FFFFFFFF',
            };
            $fg = match ($status) {
                'delivered' => 'FF065F46',
                'pending'   => 'FF92400E',
                'skipped'   => 'FF374151',
                'failed'    => 'FF991B1B',
                default     => 'FF111827',
            };
            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("F{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $fg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        $sheet->getStyle("A1:H{$last}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF2F4A1E']]],
        ]);
        $sheet->freezePane('A2');
        return [];
    }
}
