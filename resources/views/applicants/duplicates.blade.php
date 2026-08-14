@extends('layouts.app')

@section('title', 'ARS | Duplicate Applicants')

@section('content')
    <style>
        :root {
            --dup-ink: #10243d;
            --dup-slate: #5f7088;
            --dup-line: #d9e4ef;
            --dup-panel: rgb(255, 255, 255);
            --dup-primary: #1d4ed8;
            --dup-success: #059669;
            --dup-warm: #b45309;
        }

        body {
            background-color: #eef4f9;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .dup-page {
            max-width: 2000px;
        }

        .dup-shell {
            display: grid;
            gap: 1rem;
        }

        .dup-hero {
            position: relative;
            overflow: hidden;
            padding: 28px 30px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            background-color: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.08);
        }

        .dup-hero::after {
            content: "";
            position: absolute;
            right: -70px;
            top: -70px;
            width: 240px;
            height: 240px;
            border-radius: 999px;
        }

        .dup-hero>* {
            position: relative;
            z-index: 1;
        }

        .hero-top,
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .page-kicker,
        .table-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .page-kicker {
            margin-bottom: 10px;
            padding: 7px 12px;
            border-radius: 999px;
            background-color: var(--dup-warm-soft, #fef3c7);
            color: var(--dup-warm);
        }

        .dup-hero h2 {
            margin-bottom: 6px;
            color: var(--dup-ink);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-subtitle,
        .table-copy,
        .empty-copy,
        .pagination-copy {
            color: var(--dup-slate);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .metric-card,
        .dup-table-shell {
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            background-color: var(--dup-panel);
            backdrop-filter: blur(12px);
            box-shadow: 0 18px 40px rgba(15, 34, 58, 0.06);
        }

        .metric-card {
            padding: 18px 18px 16px;
        }

        .metric-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--dup-slate);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .metric-value {
            color: var(--dup-ink);
            font-size: 1.08rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .metric-copy {
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
            color: var(--dup-slate);
        }

        .dup-table-shell {
            padding: 22px;
        }

        .table-header {
            margin-bottom: 18px;
        }

        .search-card {
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid #e2ebf4;
            background: linear-gradient(180deg, #fbfdff 0%, #f8fbff 100%);
        }

        .form-label {
            margin-bottom: 7px;
            color: #44526f;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .dup-search-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 50px;
            padding: 0 14px;
            border-radius: 14px;
            border: 1px solid var(--dup-line);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .dup-search-wrap:focus-within {
            border-color: #7aa2ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .dup-search-icon {
            color: var(--dup-slate);
            font-size: 0.95rem;
        }

        .dup-search-input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--dup-ink);
            font-size: 0.95rem;
        }

        .dup-search-input::placeholder {
            color: #91a0b5;
        }

        .dup-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-ghost,
        .btn-primary-soft {
            border-radius: 14px;
            padding: 10px 18px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn-ghost {
            background: #f4f6fb;
            color: #5b6b8b;
            border: 1px solid #dce3ef;
        }

        .btn-ghost:hover {
            background: #e9efff;
            color: #2c3e50;
        }

        .btn-primary-soft {
            background-color: var(--dup-primary);
            border: none;
            color: #fff;
            box-shadow: 0 10px 22px rgba(29, 78, 216, 0.24);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(29, 78, 216, 0.28);
            color: #000000;
        }

        .group-card {
            border-radius: 22px;
            border: 1px solid #e2ebf4;
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 34, 58, 0.05);
            overflow: hidden;
        }

        .group-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 18px 22px;
            background: #f8fbff;
            border-bottom: 1px solid #e8eef6;
        }

        .group-card-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .group-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            font-size: 0.85rem;
            font-weight: 800;
        }

        .group-name {
            color: var(--dup-ink);
            font-size: 1.05rem;
            font-weight: 800;
        }

        .group-subtitle {
            color: var(--dup-slate);
            font-size: 0.82rem;
            margin-top: 2px;
        }

        .group-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .dup-table-wrap {
            overflow-x: auto;
        }

        .dup-table-wrap table {
            min-width: 600px;
        }

        .dup-table {
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .dup-table thead th {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid #e8eef6;
            background: #ffffff;
            color: #6a7b92;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }

        .dup-table tbody td {
            padding: 0.9rem 1.1rem;
            vertical-align: middle;
            border-color: #eef3f8;
        }

        .dup-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .dup-table tbody tr:hover {
            background: #fbfdff;
        }

        .applicant-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .applicant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 0.78rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .applicant-name {
            color: var(--dup-ink);
            font-weight: 700;
        }

        .applicant-meta {
            color: var(--dup-slate);
            font-size: 0.78rem;
        }

        .contact-main {
            color: var(--dup-ink);
            font-weight: 600;
        }

        .contact-meta {
            color: var(--dup-slate);
            font-size: 0.8rem;
        }

        .address-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
            word-break: break-word;
            max-width: 100%;
        }

        .btn-edit-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 700;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-edit-sm:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-dup-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            font-size: 0.8rem;
            font-weight: 700;
            border: none;
            transition: all 0.15s ease;
        }

        .btn-dup-sm:hover {
            background: #d1fae5;
            color: #047857;
        }

        .mobile-dup-list {
            display: none;
            gap: 1rem;
        }

        .mobile-dup-card {
            padding: 1rem;
            border-radius: 20px;
            border: 1px solid #e2ebf4;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 12px 28px rgba(15, 34, 58, 0.05);
        }

        .mobile-dup-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.9rem;
        }

        .mobile-dup-grid {
            display: grid;
            gap: 0.75rem;
        }

        .mobile-dup-row {
            display: grid;
            gap: 2px;
        }

        .mobile-dup-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--dup-slate);
        }

        .mobile-dup-actions {
            display: flex;
            gap: 8px;
            margin-top: 1rem;
        }

        .mobile-dup-actions .btn {
            flex: 1;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 1rem;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(16, 185, 129, 0.1);
            color: var(--dup-success);
            font-size: 2rem;
        }

        .empty-title {
            color: var(--dup-ink);
            font-size: 1rem;
            font-weight: 800;
        }

        .alert-success {
            border-radius: 18px;
            background: #ecfdf5;
            color: #065f46;
        }

        @media (max-width: 1399.98px) {
            .metrics-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 991.98px) {
            .container-fluid.dup-page {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .dup-hero {
                padding: 20px;
            }

            .dup-hero h2 {
                font-size: 1.6rem;
            }

            .hero-top,
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .dup-actions {
                width: 100%;
                justify-content: stretch;
            }

            .dup-actions .btn {
                width: 100%;
            }

            .dup-table-wrap {
                display: none;
            }

            .mobile-dup-list {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .group-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .group-card-header-left {
                width: 100%;
            }

            .group-count-badge {
                align-self: flex-start;
            }

            .group-card-header-right {
                width: 100%;
                justify-content: space-between;
            }

            .group-card {
                border-radius: 18px;
            }
        }

        @media (max-width: 767.98px) {
            .mobile-dup-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .dup-hero h2 {
                font-size: 1.35rem;
            }

            .dup-hero {
                padding: 16px;
                border-radius: 18px;
            }

            .metric-card {
                padding: 14px;
            }

            .mobile-dup-card {
                padding: 0.85rem;
                border-radius: 16px;
            }

            .mobile-dup-head {
                flex-direction: column;
                align-items: stretch;
            }

            .mobile-dup-actions {
                flex-direction: column;
            }

            .mobile-dup-actions .btn {
                width: 100%;
            }

            .group-card-header {
                padding: 14px 16px;
            }

            .dup-table-shell {
                padding: 14px;
            }
        }

        html.theme-night body {
            background: #050816;
        }

        html.theme-night .page-subtitle,
        html.theme-night .metric-copy,
        html.theme-night .table-copy,
        html.theme-night .empty-copy,
        html.theme-night .pagination-copy,
        html.theme-night .contact-meta,
        html.theme-night .mobile-dup-label,
        html.theme-night .form-label,
        html.theme-night .dup-search-icon,
        html.theme-night .group-subtitle {
            color: #94a3b8;
        }

        html.theme-night .dup-hero,
        html.theme-night .metric-card,
        html.theme-night .dup-table-shell,
        html.theme-night .search-card,
        html.theme-night .dup-table-wrap,
        html.theme-night .group-card,
        html.theme-night .mobile-dup-card,
        html.theme-night .empty-state,
        html.theme-night .dup-search-wrap,
        html.theme-night .address-pill,
        html.theme-night .pagination-wrap .page-link {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
            color: #e2e8f0;
        }

        html.theme-night .dup-hero::after {
            background: rgba(59, 130, 246, 0.08);
        }

        html.theme-night .dup-hero h2,
        html.theme-night .metric-value,
        html.theme-night .empty-title,
        html.theme-night .applicant-name,
        html.theme-night .group-name,
        html.theme-night .contact-main{
            color: #f8fafc;
        }

        html.theme-night .page-kicker {
            background: rgba(245, 158, 11, 0.16);
            color: #fcd34d;
        }

        html.theme-night .group-card-header {
            background: #111827 !important;
            border-bottom-color: rgba(148, 163, 184, 0.16) !important;
        }

        html.theme-night .dup-table thead th {
            background: #111827 !important;
            color: #cbd5e1;
            border-bottom-color: rgba(148, 163, 184, 0.16) !important;
        }

        html.theme-night .dup-table tbody td {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.12) !important;
        }

        html.theme-night .dup-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.06) !important;
        }

        html.theme-night .group-avatar {
            background: rgba(248, 113, 113, 0.16);
            color: #fca5a5;
        }

        html.theme-night .group-count-badge {
            background: rgba(248, 113, 113, 0.16);
            color: #fca5a5;
        }

        html.theme-night .applicant-avatar {
            background: #111827;
            color: #cbd5e1;
        }

        html.theme-night .btn-ghost {
            background: #111827;
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.18);
        }

        html.theme-night .btn-ghost:hover {
            background: #1f2937;
            color: #f8fafc;
        }

        html.theme-night .btn-primary-soft {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
        }

        html.theme-night .btn-edit-sm {
            background: rgba(96, 165, 250, 0.14);
            color: #93c5fd;
        }

        html.theme-night .btn-edit-sm:hover {
            background: rgba(96, 165, 250, 0.22);
            color: #bfdbfe;
        }

        html.theme-night .btn-dup-sm {
            background: rgba(52, 211, 153, 0.14);
            color: #6ee7b7;
        }

        html.theme-night .btn-dup-sm:hover {
            background: rgba(52, 211, 153, 0.22);
            color: #d1fae5;
        }

        .metric-icon {
            font-size: 0.95rem;
        }

        .metric-exact .metric-icon {
            color: #dc2626;
        }

        .metric-likely .metric-icon {
            color: #b45309;
        }

        .metric-possible .metric-icon {
            color: #1d4ed8;
        }

        .metric-total .metric-icon {
            color: var(--dup-slate);
        }

        .tier-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            padding: 13px 18px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 34, 58, 0.05);
        }

        .tier-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .tier-title {
            color: var(--dup-ink);
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .tier-criteria {
            color: var(--dup-slate);
            font-size: 0.8rem;
            margin-top: 2px;
        }

        .tier-action {
            margin-left: auto;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .tier-exact .tier-icon,
        .tier-exact .tier-action {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .tier-likely .tier-icon,
        .tier-likely .tier-action {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
        }

        .tier-possible .tier-icon,
        .tier-possible .tier-action {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        .tier-exact .group-avatar,
        .tier-exact .group-count-badge {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .tier-likely .group-avatar,
        .tier-likely .group-count-badge {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
        }

        .tier-possible .group-avatar,
        .tier-possible .group-count-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        .birthdate-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .birthdate-missing {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
        }

        .group-card-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-dismiss-sm,
        .btn-restore-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            border: none;
            transition: all 0.15s ease;
        }

        .btn-dismiss-sm {
            background: #ffffff;
            color: #1e8023;
            border: 1px solid #1e8023;
        }

        .btn-dismiss-sm:hover {
            background: rgba(97, 239, 68, 0.08);
            border-color: rgba(97, 239, 68, 0.35);
            color: #1e8023;
            border: 1px solid #1e8023;
        }

        .btn-restore-sm {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        .btn-restore-sm:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .tier-dismissed .tier-icon,
        .tier-dismissed .tier-action {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }

        .dismissed-card .group-card-header {
            background: #f6f8fb;
        }

        .dismissed-card .group-avatar {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }

        html.theme-night .metric-possible .metric-icon {
            color: #93c5fd;
        }

        html.theme-night .tier-head {
            background: #0f172a !important;
            border-color: rgba(148, 163, 184, 0.16) !important;
        }

        html.theme-night .tier-title {
            color: #f8fafc;
        }

        html.theme-night .tier-criteria {
            color: #94a3b8;
        }

        html.theme-night .tier-exact .tier-icon,
        html.theme-night .tier-exact .tier-action,
        html.theme-night .tier-exact .group-avatar,
        html.theme-night .tier-exact .group-count-badge {
            background: rgba(248, 113, 113, 0.16);
            color: #fca5a5;
        }

        html.theme-night .tier-likely .tier-icon,
        html.theme-night .tier-likely .tier-action,
        html.theme-night .tier-likely .group-avatar,
        html.theme-night .tier-likely .group-count-badge {
            background: rgba(251, 191, 36, 0.14);
            color: #fcd34d;
        }

        html.theme-night .tier-possible .tier-icon,
        html.theme-night .tier-possible .tier-action,
        html.theme-night .tier-possible .group-avatar,
        html.theme-night .tier-possible .group-count-badge {
            background: rgba(96, 165, 250, 0.16);
            color: #93c5fd;
        }

        html.theme-night .birthdate-pill {
            background: rgba(148, 163, 184, 0.14);
            color: #cbd5e1;
        }

        html.theme-night .btn-dismiss-sm {
            background: #1f2937;
            border-color: rgba(148, 163, 184, 0.25);
            color: #cbd5e1;
        }

        html.theme-night .btn-dismiss-sm:hover {
            background: rgba(97, 239, 68, 0.16);
            border-color: rgba(97, 239, 68, 0.4);
            color: #a8fca5;
        }

        html.theme-night .btn-restore-sm {
            background: rgba(96, 165, 250, 0.14);
            color: #93c5fd;
        }

        html.theme-night .btn-restore-sm:hover {
            background: rgba(96, 165, 250, 0.22);
            color: #bfdbfe;
        }

        html.theme-night .tier-dismissed .tier-icon,
        html.theme-night .tier-dismissed .tier-action {
            background: rgba(148, 163, 184, 0.14);
            color: #94a3b8;
        }

        html.theme-night .dismissed-card .group-card-header {
            background: #111827 !important;
        }

        html.theme-night .dismissed-card .group-avatar {
            background: rgba(148, 163, 184, 0.14);
            color: #94a3b8;
        }
    </style>

    <div class="container-fluid dup-page py-0 px-md-4 px-xl-0">
        <div class="dup-shell">

            <section class="dup-hero">
                <div class="hero-top">
                    <div>   
                        <h2>Duplicate Applicants</h2>
                        <p class="page-subtitle">Detect potential double entries grouped by match confidence — exact-same name and
                            birthdate, likely-same name with birthdate missing or year-only match,<br> and possible-similar
                            name spelling that needs verification before acting.</p>
                    </div>
                    <a href="{{ route('applicants.index') }}" class="btn btn-ghost">
                        <i class="bi bi-arrow-left me-2"></i>Back to Active List
                    </a>
                </div>

                <form method="GET" action="{{ route('applicants.duplicates') }}">
                    <div class="row g-3 align-items-end mt-3">
                        <div class="col-lg-8">
                            <label class="form-label">Search Duplicate Groups</label>
                            <div class="dup-search-wrap">
                                <i class="bi bi-search dup-search-icon"></i>
                                <input type="text" name="search" class="dup-search-input"
                                    placeholder="Search by name, contact number, barangay, or city..."
                                    value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="dup-actions">
                                <button type="submit" class="btn btn-primary-soft">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                                <a href="{{ route('applicants.duplicates') }}" class="btn btn-ghost">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="metrics-grid">
                <div class="metric-card metric-exact">
                    <div class="metric-label"><i class="bi bi-exclamation-octagon metric-icon"></i> Exact Matches</div>
                    <div class="metric-value">{{ number_format($exactCount) }}</div>
                    <span class="metric-copy">Same full name and exact-same birthdate</span>
                </div>
                <div class="metric-card metric-likely">
                    <div class="metric-label"><i class="bi bi-exclamation-triangle metric-icon"></i> Likely Matches</div>
                    <div class="metric-value">{{ number_format($likelyCount) }}</div>
                    <span class="metric-copy">Same name — birthdate missing or year-only match</span>
                </div>
                <div class="metric-card metric-possible">
                    <div class="metric-label"><i class="bi bi-question-circle metric-icon"></i> Possible Matches</div>
                    <div class="metric-value">{{ number_format($possibleCount) }}</div>
                    <span class="metric-copy">Similar name spelling — verify before acting</span>
                </div>
                <div class="metric-card metric-total">
                    <div class="metric-label"><i class="bi bi-people metric-icon"></i> Records Involved</div>
                    <div class="metric-value">{{ number_format($totalDuplicates) }}</div>
                    <span class="metric-copy">Applicants inside duplicate groups</span>
                </div>
            </div>

            <section class="dup-table-shell">
                <div class="table-header">
                    <div>
                        <div class="table-label">Duplicate Records</div>
                        <h5 class="fw-bold mb-1">Grouped by match confidence</h5>
                        <p class="table-copy mb-0">Records are ranked as exact, likely, or possible matches. Always
                            verify identity before acting on any duplicate group.</p>
                    </div>
                </div>

                @php($hasAny = collect($tiers)->contains(fn ($tier) => $tier['groups']->isNotEmpty()))

                @foreach($tiers as $tier)
                    @if($tier['groups']->isNotEmpty())
                        <div class="tier-head tier-{{ $tier['key'] }}">
                            <div class="tier-icon"><i class="bi {{ $tier['icon'] }}"></i></div>
                            <div>
                                <div class="tier-title">{{ $tier['label'] }}</div>
                                <div class="tier-criteria">{{ $tier['criteria'] }}</div>
                            </div>
                            <span class="tier-action">{{ $tier['action'] }}</span>
                        </div>

                        @foreach($tier['groups'] as $group)
                            <div class="group-card mb-4">
                                <div class="group-card-header">
                                    <div class="group-card-header-left">
                                        <div class="group-avatar">
                                            {{ strtoupper(substr($group['first_name'], 0, 1)) }}{{ strtoupper(substr($group['last_name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="group-name">
                                                {{ trim($group['first_name'] . ' ' . $group['last_name']) }}
                                            </div>
                                            <div class="group-subtitle">
                                                {{ $group['count'] }} records — {{ $group['reason'] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group-card-header-right">
                                    <div class="group-count-badge">
                                        <i class="bi bi-copy"></i>
                                        {{ $group['count'] }} records
                                    </div>
                                    <form method="POST" action="{{ route('applicants.duplicates.dismiss') }}" class="m-0">
                                        @csrf
                                        @foreach($group['applicants'] as $applicant)
                                            <input type="hidden" name="applicant_ids[]" value="{{ $applicant->id }}">
                                        @endforeach
                                        <button type="submit" class="btn-dismiss-sm" title="Mark this group as not a duplicate">
                                            <i class="bi bi-check-square"></i> Not a duplicate
                                        </button>
                                    </form>
                                </div>
                                </div>

                                <div class="dup-table-wrap">
                                    <table class="table dup-table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Applicant</th>
                                                <th>Birthdate</th>
                                                <th>Contact</th>
                                                <th>Address</th>
                                                <th>Created</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($group['applicants'] as $applicant)
                                                <tr>
                                                    <td>
                                                        <div class="applicant-cell">
                                                            <div class="applicant-avatar">
                                                                {{ strtoupper(substr($applicant->first_name, 0, 1)) }}{{ strtoupper(substr($applicant->last_name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="applicant-name">
                                                                    {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                                                </div>
                                                                <div class="applicant-meta">ID:
                                                                    #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($applicant->birthdate)
                                                            <div class="contact-main">{{ $applicant->birthdate->format('M d, Y') }}</div>
                                                        @else
                                                            <span class="birthdate-pill birthdate-missing">Not recorded</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="contact-main">{{ $applicant->contact_no ?: 'N/A' }}</div>
                                                        <div class="contact-meta">{{ $applicant->gender ?: 'N/A' }} /
                                                            {{ $applicant->civil_status ?: 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="address-pill">
                                                            {{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="contact-main">
                                                            {{ $applicant->created_at?->format('M d, Y') ?? 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('applicants.edit', $applicant->id) }}"
                                                            class="btn-edit-sm" title="View Applicant">
                                                            <i class="bi bi-eye-fill"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mobile-dup-list">
                                    @foreach($group['applicants'] as $applicant)
                                        <article class="mobile-dup-card">
                                            <div class="mobile-dup-head">
                                                <div class="applicant-cell">
                                                    <div class="applicant-avatar">
                                                        {{ strtoupper(substr($applicant->first_name, 0, 1)) }}{{ strtoupper(substr($applicant->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="applicant-name">
                                                            {{ trim($applicant->first_name . ' ' . ($applicant->middle_name ? strtoupper(substr($applicant->middle_name, 0, 1)) . '. ' : '') . $applicant->last_name . ' ' . ($applicant->suffix ?? '')) }}
                                                        </div>
                                                        <div class="applicant-meta">ID:
                                                            #{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mobile-dup-grid">
                                                <div class="mobile-dup-row">
                                                    <div class="mobile-dup-label">Birthdate</div>
                                                    @if($applicant->birthdate)
                                                        <div class="contact-main">{{ $applicant->birthdate->format('M d, Y') }}</div>
                                                    @else
                                                        <span class="birthdate-pill birthdate-missing">Not recorded</span>
                                                    @endif
                                                </div>
                                                <div class="mobile-dup-row">
                                                    <div class="mobile-dup-label">Contact</div>
                                                    <div class="contact-main">{{ $applicant->contact_no ?: 'N/A' }}</div>
                                                    <div class="contact-meta">{{ $applicant->gender ?: 'N/A' }} /
                                                        {{ $applicant->civil_status ?: 'N/A' }}</div>
                                                </div>
                                                <div class="mobile-dup-row">
                                                    <div class="mobile-dup-label">Address</div>
                                                    <span class="address-pill">
                                                        {{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mobile-dup-actions">
                                                <a href="{{ route('applicants.edit', $applicant->id) }}" class="btn btn-edit-sm">
                                                    <i class="bi bi-eye-fill"></i> View
                                                </a>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach

                @if($dismissedGroups->isNotEmpty())
                    <div class="tier-head tier-dismissed">
                        <div class="tier-icon"><i class="bi bi-x-circle"></i></div>
                        <div>
                            <div class="tier-title">Not Duplicates</div>
                            <div class="tier-criteria">Groups marked as not duplicates by reviewers. They stay hidden
                                until restored.</div>
                        </div>
                        <span class="tier-action">{{ $dismissedGroups->count() }} dismissed</span>
                    </div>

                    @foreach($dismissedGroups as $group)
                        <div class="group-card dismissed-card mb-4">
                            <div class="group-card-header">
                                <div class="group-card-header-left">
                                    <div class="group-avatar">
                                        {{ strtoupper(substr($group['first_name'], 0, 1)) }}{{ strtoupper(substr($group['last_name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="group-name">
                                            {{ trim($group['first_name'] . ' ' . $group['last_name']) }}
                                        </div>
                                        <div class="group-subtitle">
                                            {{ $group['count'] }} records — marked not a duplicate on {{ $group['dismissed_at']?->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('applicants.duplicates.restore') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="group_hash" value="{{ $group['hash'] }}">
                                    <button type="submit" class="btn-restore-sm" title="Restore this group to the duplicate list">
                                        <i class="bi bi-arrow-counterclockwise"></i> Undo
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(!$hasAny)
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="empty-title">No duplicate applicants found</div>
                        <p class="empty-copy mb-0">All applicant records are unique. No exact, likely, or possible
                            duplicate matches were detected.</p>
                    </div>
                @endif
            </section>

        </div>
    </div>
@endsection
