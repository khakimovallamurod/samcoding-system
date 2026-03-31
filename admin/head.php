<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SamCoding — Admin</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        sidebar: '#0f172a',
                        'sidebar-hover': '#1e293b',
                        'sidebar-active': '#2563eb',
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap 4 (kept for inner pages using .row/.col grid) -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- DataTables (standard theme) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* ── Base layout ── */
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            margin: 0; padding: 0;
            width: 100%; min-height: 100%;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f1f5f9;
        }
        /* Admin page root wrapper */
        .admin-root {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background: #f1f5f9;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 256px;
            height: 100vh;
            background: #0f172a;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

        /* ── Content wrapper ── */
        #content {
            margin-left: 256px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            width: calc(100% - 256px);
        }

        /* ── Topbar ── */
        #topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        /* ── Main area ── */
        #main-content {
            flex: 1;
            padding: 24px;
        }

        /* ── Sidebar nav items ── */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #94a3b8;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            margin: 1px 8px;
            transition: all 0.15s ease;
        }
        .nav-item:hover {
            background: #1e293b;
            color: #e2e8f0;
            text-decoration: none;
        }
        .nav-item.active {
            background: #2563eb;
            color: #fff;
        }
        .nav-item .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* ── Section header in sidebar ── */
        .nav-section {
            padding: 16px 16px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
        }

        /* ── Stat cards ── */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        /* ── Content card ── */
        .content-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body { padding: 0; }

        /* ── Status badges ── */
        .badge-accepted  { background: #dcfce7; color: #15803d; border-radius: 999px; padding: 2px 10px; font-size: 12px; font-weight: 600; }
        .badge-wrong     { background: #fee2e2; color: #dc2626; border-radius: 999px; padding: 2px 10px; font-size: 12px; font-weight: 600; }
        .badge-runtime   { background: #fef3c7; color: #d97706; border-radius: 999px; padding: 2px 10px; font-size: 12px; font-weight: 600; }
        .badge-pending   { background: #f1f5f9; color: #64748b; border-radius: 999px; padding: 2px 10px; font-size: 12px; font-weight: 600; }

        /* ── Difficulty badges ── */
        .diff-beginner { background: #dcfce7; color: #15803d; }
        .diff-easy     { background: #dbeafe; color: #1d4ed8; }
        .diff-medium   { background: #fef3c7; color: #b45309; }
        .diff-hard     { background: #fee2e2; color: #b91c1c; }
        .diff-expert   { background: #f3e8ff; color: #7e22ce; }
        .diff-badge {
            border-radius: 999px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* ── Admin table ── */
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .admin-table thead th {
            background: #f8fafc;
            padding: 11px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }
        .admin-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
            vertical-align: middle;
        }
        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover td { background: #f8fafc; }

        /* ── Mobile sidebar toggle ── */
        #sidebarOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            #content { margin-left: 220px; width: calc(100% - 220px); }
            #sidebar { width: 220px; }
        }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: 256px; }
            #sidebar.open { transform: translateX(0); }
            #sidebarOverlay.show { display: block; }
            #content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
