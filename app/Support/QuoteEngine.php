<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Turns wizard answers into an hours and price range.
 *
 * Every number lives in the `quote_config` setting, so rates and multipliers
 * are editable from the admin panel without touching code. The same rules run
 * in the browser for the live estimate and again here on submit, so what the
 * visitor saw is what gets stored.
 */
class QuoteEngine
{
    public static function config(): array
    {
        $raw = Setting::get('quote_config');
        $decoded = $raw ? json_decode($raw, true) : null;

        // A partial override should only replace the groups it names, so a
        // half-finished edit in the panel can never break the wizard.
        return is_array($decoded)
            ? array_replace(self::defaults(), $decoded)
            : self::defaults();
    }

    public static function defaults(): array
    {
        return [
            // Baseline hours for a medium project, per service.
            'services' => [
                'architecture-design'        => ['label' => 'Architecture design', 'hours' => 60],
                'revit-drafting'             => ['label' => 'Revit drafting & BIM', 'hours' => 70],
                'construction-permit-sets'   => ['label' => 'Construction & permit sets', 'hours' => 55],
                '3d-modeling-rendering'      => ['label' => '3D modelling & rendering', 'hours' => 25],
                '3d-architectural-animation' => ['label' => '3D animation', 'hours' => 45],
                'point-cloud-to-bim'         => ['label' => 'Point cloud to BIM', 'hours' => 50],
                'interior-design'            => ['label' => 'Interior design', 'hours' => 40],
                'landscape-architecture'     => ['label' => 'Landscape architecture', 'hours' => 30],
            ],
            'size' => [
                'small'  => ['label' => 'Under 2,000 sq ft', 'factor' => 0.6],
                'medium' => ['label' => '2,000 – 6,000 sq ft', 'factor' => 1.0],
                'large'  => ['label' => '6,000 – 20,000 sq ft', 'factor' => 1.7],
                'xl'     => ['label' => 'Over 20,000 sq ft', 'factor' => 2.6],
            ],
            'scope' => [
                'concept' => ['label' => 'Concept only', 'factor' => 0.5],
                'design'  => ['label' => 'Design development', 'factor' => 1.0],
                'permit'  => ['label' => 'Permit-ready set', 'factor' => 1.35],
                'full'    => ['label' => 'Concept to permit', 'factor' => 1.8],
            ],
            'timeline' => [
                'standard' => ['label' => 'Standard', 'factor' => 1.0],
                'priority' => ['label' => 'Priority', 'factor' => 1.15],
                'rush'     => ['label' => 'Rush', 'factor' => 1.3],
            ],
            'engagement' => [
                'subscription' => ['label' => 'Monthly subscription', 'rate' => 11.84],
                'hourly'       => ['label' => 'Hourly', 'rate' => 28],
                'fixed'        => ['label' => 'Fixed price', 'rate' => 22],
            ],
            'spread' => 0.18,     // ± around the midpoint
            'minimum' => 250,     // no quote below this
        ];
    }

    /**
     * @param  array  $input  services[], size, scope, timeline, engagement
     */
    public static function estimate(array $input): array
    {
        $c = self::config();

        $hours = 0;
        $picked = [];
        foreach ((array) ($input['services'] ?? []) as $slug) {
            if (! isset($c['services'][$slug])) {
                continue;
            }
            $hours += $c['services'][$slug]['hours'];
            $picked[] = $c['services'][$slug]['label'];
        }

        // Nothing chosen still deserves a sensible starting point.
        if ($hours === 0) {
            $hours = 40;
        }

        $sizeF = $c['size'][$input['size'] ?? 'medium']['factor'] ?? 1.0;
        $scopeF = $c['scope'][$input['scope'] ?? 'design']['factor'] ?? 1.0;
        $timeF = $c['timeline'][$input['timeline'] ?? 'standard']['factor'] ?? 1.0;

        $engagement = $input['engagement'] ?? 'subscription';
        $rate = $c['engagement'][$engagement]['rate'] ?? 11.84;

        // Multiple disciplines on one project share context, so the total is
        // less than the parts. Taper anything beyond the first service.
        $count = max(1, count($picked));
        $overlap = $count > 1 ? (1 - min(0.18, ($count - 1) * 0.045)) : 1;

        $total = $hours * $sizeF * $scopeF * $overlap;
        $spread = $c['spread'] ?? 0.18;

        $hoursLow = (int) round($total * (1 - $spread));
        $hoursHigh = (int) round($total * (1 + $spread));

        $priceLow = (int) max($c['minimum'] ?? 250, round($hoursLow * $rate * $timeF / 10) * 10);
        $priceHigh = (int) max($priceLow, round($hoursHigh * $rate * $timeF / 10) * 10);

        return [
            'hours_low' => $hoursLow,
            'hours_high' => $hoursHigh,
            'price_low' => $priceLow,
            'price_high' => $priceHigh,
            'recommended_plan' => self::plan($hoursHigh, $engagement),
            'breakdown' => [
                'services' => $picked,
                'base_hours' => $hours,
                'size_factor' => $sizeF,
                'scope_factor' => $scopeF,
                'timeline_factor' => $timeF,
                'multi_service_discount' => round((1 - $overlap) * 100) . '%',
                'rate' => $rate,
            ],
        ];
    }

    protected static function plan(int $hours, string $engagement): string
    {
        if ($engagement === 'hourly') {
            return 'Hourly — $28/hr';
        }
        if ($engagement === 'fixed') {
            return 'Fixed price';
        }

        return $hours > 200 ? 'Standard — $2,500/mo' : 'Basic — $1,895/mo';
    }
}
