<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fast Food Restaurant Permission Groups
    |--------------------------------------------------------------------------
    |
    | Clean, modular groups of permissions for easy role management.
    |
    */
    'groups' => [
        'pos' => [
            'title' => 'POS & Billing',
            'description' => 'Counter order taking, touch billing, payments, and thermal printing.',
            'permissions' => [
                'pos.access' => [
                    'label' => 'Access POS Terminal',
                    'desc' => 'Open the touch POS billing screen and browse menu items.'
                ],
                'pos.create_order' => [
                    'label' => 'Create & Save Orders',
                    'desc' => 'Punch dine-in, takeaway, and delivery orders.'
                ],
                'pos.settle_payment' => [
                    'label' => 'Settle Payments',
                    'desc' => 'Collect cash/card/online payments and complete invoices.'
                ],
                'pos.apply_discount' => [
                    'label' => 'Apply Discounts',
                    'desc' => 'Apply discounts (%) on counter transactions.'
                ],
                'pos.drafts' => [
                    'label' => 'Draft Orders',
                    'desc' => 'Hold and resume active customer draft orders.'
                ],
                'pos.print_bill' => [
                    'label' => 'Print Customer Bill',
                    'desc' => 'Print thermal customer receipt slips.'
                ],
                'pos.print_kot' => [
                    'label' => 'Print Kitchen KOT',
                    'desc' => 'Print thermal kitchen order tokens.'
                ],
                'pos.cancel_order' => [
                    'label' => 'Cancel / Void Orders',
                    'desc' => 'Cancel in-progress orders or void unpaid transactions.'
                ],
            ]
        ],

        'kitchen' => [
            'title' => 'Kitchen Display (KDS)',
            'description' => 'Kitchen screen, live SLA timers, and bump status.',
            'permissions' => [
                'kitchen.access' => [
                    'label' => 'Access Kitchen Display',
                    'desc' => 'Open the full-screen live kitchen display monitor.'
                ],
                'kitchen.manage_orders' => [
                    'label' => 'Update Cooking Status',
                    'desc' => 'Mark orders as Cooking, Ready (Bump), or Served.'
                ],
            ]
        ],

        'sales' => [
            'title' => 'Sales Invoices & Cashier Scoping',
            'description' => 'Multi-cashier isolation and invoice management.',
            'permissions' => [
                'sales.view_all' => [
                    'label' => 'View All Cashiers Sales',
                    'desc' => 'View sales and invoices created by ALL cashiers and shifts (Manager/Admin).'
                ],
                'sales.view_own' => [
                    'label' => 'View Own Sales Only',
                    'desc' => 'Restricts staff to seeing only transactions created during their own shift.'
                ],
                'sales.edit' => [
                    'label' => 'Edit Saved Invoices',
                    'desc' => 'Modify customer, table, or items on past invoices.'
                ],
                'sales.delete' => [
                    'label' => 'Delete Invoices',
                    'desc' => 'Delete recorded sales invoices from the system.'
                ],
            ]
        ],

        'menu' => [
            'title' => 'Menu, Deals & Categories',
            'description' => 'Manage food catalog, combo deals, and categories.',
            'permissions' => [
                'products.view' => [
                    'label' => 'View Menu Items',
                    'desc' => 'View list of food dishes and prices.'
                ],
                'products.create' => [
                    'label' => 'Add Menu Items',
                    'desc' => 'Create new burgers, pizzas, sides, and drinks.'
                ],
                'products.edit' => [
                    'label' => 'Edit Menu Items',
                    'desc' => 'Update selling prices, details, and photos.'
                ],
                'products.delete' => [
                    'label' => 'Delete Menu Items',
                    'desc' => 'Remove food items from the catalog.'
                ],
                'deals.view' => [
                    'label' => 'View Deals & Combos',
                    'desc' => 'View combo packages.'
                ],
                'deals.manage' => [
                    'label' => 'Manage Deals & Combos',
                    'desc' => 'Create and edit combo deals with multiple items.'
                ],
                'categories.view' => [
                    'label' => 'View Categories',
                    'desc' => 'View menu categories.'
                ],
                'categories.manage' => [
                    'label' => 'Manage Categories',
                    'desc' => 'Create, edit, or delete menu categories.'
                ],
            ]
        ],

        'inventory' => [
            'title' => 'Purchases & Suppliers',
            'description' => 'Stock purchases, ingredient buying, and suppliers.',
            'permissions' => [
                'purchases.view' => [
                    'label' => 'View Purchases',
                    'desc' => 'View stock purchases history and purchase invoices.'
                ],
                'purchases.manage' => [
                    'label' => 'Create Purchases (Stock-In)',
                    'desc' => 'Record ingredient buying and supplier settlements.'
                ],
                'suppliers.manage' => [
                    'label' => 'Manage Suppliers',
                    'desc' => 'Add and manage vendor / supplier profiles.'
                ],
            ]
        ],

        'expenses_customers' => [
            'title' => 'Expenses & Customers',
            'description' => 'Daily operational expenses and customer directory.',
            'permissions' => [
                'expenses.view' => [
                    'label' => 'View Expenses',
                    'desc' => 'View daily expense records.'
                ],
                'expenses.manage' => [
                    'label' => 'Add & Manage Expenses',
                    'desc' => 'Record operational expense vouchers.'
                ],
                'members.manage' => [
                    'label' => 'Manage Customers',
                    'desc' => 'Register and edit customer loyalty contacts.'
                ],
            ]
        ],

        'reports' => [
            'title' => 'Reports & Analytics',
            'description' => 'Store performance, daily profit & loss, and PDF exports.',
            'permissions' => [
                'reports.view' => [
                    'label' => 'View Profit & Sales Reports',
                    'desc' => 'View revenue, expenses, and net profit analytics.'
                ],
                'reports.export' => [
                    'label' => 'Export PDF Reports',
                    'desc' => 'Download PDF reports of sales and profits.'
                ],
            ]
        ],

        'admin' => [
            'title' => 'Staff & Store Settings',
            'description' => 'Staff user accounts, roles, and restaurant settings.',
            'permissions' => [
                'users.manage' => [
                    'label' => 'Manage Staff & Roles',
                    'desc' => 'Create users, configure roles, and customize permissions.'
                ],
                'settings.manage' => [
                    'label' => 'Restaurant Settings',
                    'desc' => 'Edit restaurant name, logo, phone, address, and receipt notes.'
                ],
            ]
        ],
    ]
];
