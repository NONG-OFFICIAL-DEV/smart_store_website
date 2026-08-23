<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

// Seeds the CMS-driven Feature Showcase content for Nexstack POS's product
// detail page (see frontend's ProductFeatureSections.vue + the 4 renderer
// components it dispatches to). Only nexstack-pos has content today — any
// other product with no rows here simply falls back to the older generic
// Features/Screenshots grids (see pages/products/[slug].vue).
class ProductFeatureSectionSeeder extends Seeder
{
    public function run(): void
    {
        $pos = Product::where('slug', 'nexstack-pos')->first();
        if (!$pos) {
            return;
        }

        // ── 1. Feature overview grid ──────────────────────────────────────
        $grid = $pos->featureSections()->create(['type' => 'feature-grid', 'sort_order' => 1]);
        $grid->translations()->create([
            'locale' => 'en',
            'title' => 'Everything you need to run service',
            'subtitle' => 'One system covers the whole floor, from the counter to the kitchen to the back office.',
        ]);
        foreach ([
            ['mdi-cash-register', null, 'POS & Checkout', 'Fast, reliable checkout for busy floors and counters.'],
            ['mdi-warehouse', 'popular', 'Inventory Management', 'Track stock, purchase orders, and movements across every branch.'],
            ['mdi-book-open-page-variant-outline', null, 'Digital Menu', "Update prices and items instantly — no reprinting needed."],
            ['mdi-monitor-dashboard', 'new', 'Kitchen Display System', "Orders appear on the kitchen screen the moment they're placed."],
            ['mdi-receipt-text-outline', null, 'Receipt & Printing', 'Clean receipts every time, with full Khmer script support.'],
            ['mdi-chart-line', null, 'Sales & Reports', 'Real-time revenue, best sellers, and staff performance.'],
            ['mdi-account-group-outline', null, 'Staff Management', 'Roles, permissions, and shift tracking per branch.'],
            ['mdi-source-branch', 'pro', 'Multi-Branch Management', 'Run one branch or twenty from a single account.'],
        ] as $i => [$icon, $badge, $title, $desc]) {
            $item = $grid->items()->create(['icon' => $icon, 'badge' => $badge, 'sort_order' => $i + 1]);
            $item->translations()->create(['locale' => 'en', 'title' => $title, 'description' => $desc]);
        }

        // ── 2. Interactive showcase ────────────────────────────────────────
        $showcase = $pos->featureSections()->create(['type' => 'feature-showcase', 'sort_order' => 2]);
        $showcase->translations()->create([
            'locale' => 'en',
            'title' => 'See it in action',
            'subtitle' => 'Explore what each part of Nexstack POS actually does.',
        ]);
        foreach ([
            ['mdi-cash-register', 'POS',
                'Powerful POS for faster service — take orders, manage products, process payments, and complete sales from one simple interface.',
                ['Fast checkout', 'Multiple payment methods', 'Discounts', 'Receipt printing']],
            ['mdi-warehouse', 'Inventory',
                "Always know what's in stock — track stock levels, low-stock alerts, and purchase orders across every branch.",
                ['Stock tracking', 'Low-stock alerts', 'Purchase management', 'Stock transfers']],
            ['mdi-book-open-page-variant-outline', 'Digital Menu',
                'A menu that updates itself — change prices, add items, or mark something sold out instantly across every device.',
                ['Instant price updates', 'Category management', 'Sold-out toggling', 'Multi-language menu']],
            ['mdi-monitor-dashboard', 'Kitchen',
                "Orders that never get lost — every order appears on the kitchen screen the moment it's placed, and clears the moment it's done.",
                ['Real-time order queue', 'Course timing', 'Order status tracking']],
            ['mdi-chart-line', 'Reports',
                'Know your numbers, live — see revenue, best sellers, and staff performance the moment a sale happens, not after closing.',
                ['Live revenue tracking', 'Best-seller breakdown', 'Staff performance', 'Branch comparison']],
        ] as $i => [$icon, $title, $desc, $benefits]) {
            $item = $showcase->items()->create(['icon' => $icon, 'sort_order' => $i + 1]);
            $item->translations()->create([
                'locale' => 'en',
                'title' => $title,
                'description' => $desc,
                'benefits' => $benefits,
            ]);
        }

        // ── 3. Alternating feature detail blocks ──────────────────────────
        $detail = $pos->featureSections()->create(['type' => 'feature-detail', 'sort_order' => 3]);
        $detail->translations()->create([
            'locale' => 'en',
            'title' => 'Built for how service actually works',
            'subtitle' => null,
        ]);
        foreach ([
            ['mdi-cash-register', 'Powerful Checkout', 'Process orders quickly and keep your queue moving, even during the rush.',
                ['Fast checkout', 'Discounts', 'Multiple payments']],
            ['mdi-warehouse', 'Inventory Management', 'Keep track of stock, low-stock products, and product movements without spreadsheets.',
                ['Stock tracking', 'Low-stock alerts', 'Purchase management']],
        ] as $i => [$icon, $title, $desc, $benefits]) {
            $item = $detail->items()->create(['icon' => $icon, 'sort_order' => $i + 1]);
            $item->translations()->create([
                'locale' => 'en',
                'title' => $title,
                'description' => $desc,
                'benefits' => $benefits,
            ]);
        }

        // ── 4. Workflow ─────────────────────────────────────────────────────
        $workflow = $pos->featureSections()->create(['type' => 'workflow', 'sort_order' => 4]);
        $workflow->translations()->create([
            'locale' => 'en',
            'title' => 'How it all connects',
            'subtitle' => "From the moment an order comes in to the moment it's counted.",
        ]);
        foreach ([
            ['mdi-account-outline', 'Customer Order', 'A customer places an order at the table or counter.'],
            ['mdi-cash-register', 'POS Checkout', 'Staff ring it up — items, quantities, and any discounts.'],
            ['mdi-chef-hat', 'Kitchen', 'The order appears on the kitchen screen instantly.'],
            ['mdi-check-circle-outline', 'Order Completed', 'Kitchen marks it done; front-of-house is notified.'],
            ['mdi-credit-card-outline', 'Payment', 'Customer pays by cash, card, or digital wallet.'],
            ['mdi-chart-line', 'Sales Report', 'The sale flows straight into live revenue reports.'],
        ] as $i => [$icon, $title, $desc]) {
            $item = $workflow->items()->create(['icon' => $icon, 'sort_order' => $i + 1]);
            $item->translations()->create(['locale' => 'en', 'title' => $title, 'description' => $desc]);
        }
    }
}
