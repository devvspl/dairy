<?php

namespace App\Exports;

use App\Models\DeliveryLog;
use App\Models\Location;
use App\Models\MilkPrice;
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
        $query = DeliveryLog::with(['subscription.user', 'subscription.membershipPlan', 'subscription.deliverySettings'])
            ->whereHas('subscription', fn($q) => $q->where('location_id', $this->location->id))
            ->whereDate('delivery_date', $this->date)
            ->orderBy('status');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $rows = $query->get()->map(function ($d) {
            $sub       = $d->subscription;
            $milkItems = $d->milk_items ?? [];

            // Fallback to delivery settings if log has no milk_items
            if (empty($milkItems) && $sub->deliverySettings) {
                $milkItems = $sub->deliverySettings->getMilkItemsResolved();
            }

            // Build milk items string: "Cow 1L ₹70/L, Buffalo 2L ₹80/L"
            $milkStr = '';
            $dailyCost = 0;
            if (!empty($milkItems)) {
                $parts = [];
                foreach ($milkItems as $item) {
                    $ppl = (float)($item['ppl'] ?? 0);
                    if (!$ppl) {
                        $mp  = MilkPrice::forType($item['milk_type'] ?? '');
                        $ppl = $mp ? (float)$mp->price_per_litre : 0;
                    }
                    $label   = ucfirst(str_replace('_', ' ', $item['milk_type'] ?? ''));
                    $qty     = (float)($item['qty'] ?? 0);
                    $slot    = ucfirst($item['slot'] ?? '');
                $parts[] = "{$label} {$qty}L" . ($ppl ? " @\u{20B9}{$ppl}/L" : '') . ($slot ? " ({$slot})" : '');
                    $dailyCost += $ppl * $qty;
                }
                $milkStr = implode(chr(10), $parts);
            } elseif ($sub->milk_type) {
                $ppl      = (float)($sub->price_per_litre ?? 0);
                $milkStr  = ucfirst(str_replace('_', ' ', $sub->milk_type));
                $milkStr .= $ppl ? " @₹{$ppl}/L" : '';
                $dailyCost = $ppl * (float)($sub->quantity_per_day ?? 0);
            }

            return [
                'Customer'    => $sub->user->name ?? '-',
                'Phone'       => $sub->user->phone ?? '-',
                'Address'     => $sub->delivery_address ?? '-',
                'Instructions'=> $sub->delivery_instructions ?? '-',
                'Milk Items'  => $milkStr ?: '-',
                'Total Qty'   => (float)$d->quantity_delivered . 'L',
                'Daily Cost'  => $dailyCost > 0 ? '₹' . number_format($dailyCost, 2) : '-',
                'Wallet Bal'  => $sub->wallet_balance !== null ? '₹' . number_format($sub->wallet_balance, 2) : '-',
                'Status'      => ucfirst($d->status),
                'Time'        => $d->delivery_time
                                    ? \Carbon\Carbon::parse($d->delivery_time)->format('h:i A')
                                    : '-',
                'Notes'       => $d->notes ?? '-',
            ];
        });

        $this->rowCount = $rows->count();
        return $rows;
    }

    public function headings(): array
    {
        return ['Customer', 'Phone', 'Address', 'Instructions', 'Milk Items', 'Total Qty', 'Daily Cost', 'Wallet Bal', 'Status', 'Time', 'Notes'];
    }

    public function title(): string
    {
        return $this->location->name . ' ' . \Carbon\Carbon::parse($this->date)->format('d-M-Y');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24, 'B' => 15, 'C' => 32, 'D' => 28,
            'E' => 30, 'F' => 12, 'G' => 14, 'H' => 14,
            'I' => 12, 'J' => 12, 'K' => 28,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $last    = $sheet->getHighestRow();
        $lastCol = 'K';

        // Header row
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2F4A1E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1A2E0F']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Wrap text for milk items column (E) and auto row height
        $sheet->getStyle("E2:E{$last}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("D2:D{$last}")->getAlignment()->setWrapText(true);

        for ($row = 2; $row <= $last; $row++) {
            $status = strtolower((string) $sheet->getCell("I{$row}")->getValue());
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
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("I{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $fg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            // Auto row height — taller for multi-line milk items
            $cellVal   = (string) $sheet->getCell("E{$row}")->getValue();
            $lineCount = max(1, substr_count($cellVal, chr(10)) + 1);
            $sheet->getRowDimension($row)->setRowHeight($lineCount > 1 ? $lineCount * 16 : 18);
        }

        $sheet->getStyle("A1:{$lastCol}{$last}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF2F4A1E']]],
        ]);
        $sheet->freezePane('A2');
        return [];
    }
}
