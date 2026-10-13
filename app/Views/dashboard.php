<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --card-border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --primary: #3b82f6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            line-height: 1.5;
        }

        .dashboard-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            width: 100%;
            max-width: 500px;
            padding: 2.25rem 2rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }

        .header {
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .user-details {
            background-color: #0f172a;
            border: 1px solid var(--card-border);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #1e293b;
            font-size: 0.9rem;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .detail-value {
            color: var(--text-main);
            font-weight: 600;
        }

        .btn-logout {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--danger);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            font-family: inherit;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-logout:hover {
            background-color: var(--danger-hover);
        }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <div class="header">
            <h1>Selamat Datang!</h1>
            <p>Anda berhasil login ke dalam sistem.</p>
        </div>

        <div class="user-details">
            <div class="detail-row">
                <span class="detail-label">Nama</span>
                <span class="detail-value"><?= esc($user['name'] ?? '-') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Username</span>
                <span class="detail-value"><?= esc($user['username'] ?? '-') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?= esc($user['email'] ?? '-') ?></span>
            </div>
        </div>

        <!-- Logout menggunakan method POST -->
        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</body>
</html>
