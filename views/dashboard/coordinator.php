<?php
$title = 'Dashboard';
include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(136, 219, 242, 0.25);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    .page-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg,
            var(--stormy-cyan) 0%,
            var(--stormy-light) 50%,
            var(--stormy-cyan) 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .header-left {
        flex: 1;
    }

    .header-icon-title {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1rem;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 8px 24px rgba(136, 219, 242, 0.35),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .header-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .header-title-wrapper h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--stormy-dark);
        margin: 0;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .header-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .header-stats {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.1) 0%,
            rgba(189, 221, 252, 0.1) 100%);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 12px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--stormy-dark);
        box-shadow: 0 2px 8px rgba(136, 219, 242, 0.1);
        transition: all 0.3s ease;
    }

    .stat-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(136, 219, 242, 0.2);
    }

    .stat-pill i {
        color: var(--stormy-cyan);
        font-size: 0.875rem;
    }

    .stat-pill-value {
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Dashboard Grid Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Metrics Section - 8 columns */
    .metrics-section {
        grid-column: span 8;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .metric-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--stormy-cyan), var(--stormy-blue));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        border-color: var(--stormy-cyan);
        box-shadow:
            0 16px 48px rgba(136, 219, 242, 0.2),
            0 8px 16px rgba(56, 73, 89, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    .metric-card:hover::before {
        transform: scaleX(1);
    }

    .metric-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .metric-label {
        font-size: 0.8125rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .metric-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-badge.urgent {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.15));
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .metric-badge.active {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.15), rgba(189, 221, 252, 0.15));
        color: var(--stormy-blue);
        border: 1px solid rgba(136, 219, 242, 0.3);
    }

    .metric-badge.positive {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(22, 163, 74, 0.15));
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .metric-badge.warning {
        background: linear-gradient(135deg, rgba(251, 146, 60, 0.15), rgba(249, 115, 22, 0.15));
        color: #ea580c;
        border: 1px solid rgba(251, 146, 60, 0.3);
    }

    .metric-value {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 0.75rem;
        letter-spacing: -2px;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .metric-description {
        font-size: 0.9375rem;
        color: #64748b;
        font-weight: 500;
        line-height: 1.6;
    }

    /* Featured Card - 4 columns */
    .featured-section {
        grid-column: span 4;
    }

    .featured-card {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        border-radius: 24px;
        padding: 2.5rem;
        height: 100%;
        box-shadow:
            0 16px 48px rgba(106, 137, 167, 0.35),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.4s backwards;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .featured-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .featured-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .featured-icon i {
        font-size: 2rem;
        color: white;
    }

    .featured-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1rem;
    }

    .featured-value {
        font-size: 3.5rem;
        font-weight: 900;
        color: white;
        line-height: 1;
        margin-bottom: 1rem;
        letter-spacing: -2px;
        text-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .featured-description {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        line-height: 1.6;
    }

    /* Activity Section - Full Width */
    .activity-section {
        grid-column: span 12;
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        animation: fadeInUp 0.6s ease 0.6s backwards;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .section-title-wrapper h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--stormy-dark);
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.5px;
    }

    .section-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .section-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(136, 219, 242, 0.3);
        transition: all 0.3s ease;
    }

    .section-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(136, 219, 242, 0.4);
    }

    /* Patient Cards */
    .patients-grid {
        display: grid;
        gap: 1.25rem;
    }

    .patient-card {
        background: linear-gradient(135deg,
            rgba(250, 252, 255, 0.8) 0%,
            rgba(255, 255, 255, 0.6) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .patient-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 16px 0 0 16px;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .patient-card:hover {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(255, 255, 255, 0.9) 100%);
        border-color: var(--stormy-cyan);
        box-shadow:
            0 8px 24px rgba(136, 219, 242, 0.2),
            0 4px 8px rgba(56, 73, 89, 0.08);
        transform: translateX(4px);
    }

    .patient-card:hover::before {
        transform: scaleY(1);
    }

    .patient-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow:
            0 8px 24px rgba(136, 219, 242, 0.35),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        letter-spacing: -0.5px;
    }

    .patient-info {
        flex: 1;
        min-width: 0;
    }

    .patient-name {
        font-size: 1.0625rem;
        font-weight: 800;
        color: var(--stormy-dark);
        margin-bottom: 0.625rem;
        letter-spacing: -0.25px;
    }

    .patient-details {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .patient-detail {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8125rem;
        color: #64748b;
        font-weight: 500;
    }

    .patient-detail i {
        color: var(--stormy-cyan);
        font-size: 0.75rem;
    }

    .patient-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-end;
    }

    .patient-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .patient-badge.success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(22, 163, 74, 0.15));
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .patient-badge.info {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.15), rgba(189, 221, 252, 0.15));
        color: var(--stormy-blue);
        border: 1px solid rgba(136, 219, 242, 0.3);
    }

    .patient-badge.warning {
        background: linear-gradient(135deg, rgba(251, 146, 60, 0.15), rgba(249, 115, 22, 0.15));
        color: #ea580c;
        border: 1px solid rgba(251, 146, 60, 0.3);
    }

    .view-patient-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: white;
        border: 2px solid rgba(136, 219, 242, 0.3);
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(136, 219, 242, 0.1);
    }

    .view-patient-btn:hover {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-color: transparent;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(136, 219, 242, 0.35);
    }

    .view-patient-btn i {
        transition: transform 0.3s ease;
    }

    .view-patient-btn:hover i {
        transform: translateX(3px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.05) 0%,
            rgba(189, 221, 252, 0.05) 100%);
        border-radius: 20px;
        border: 2px dashed rgba(136, 219, 242, 0.3);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.1) 0%,
            rgba(189, 221, 252, 0.1) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--stormy-cyan);
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #64748b;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        background: white;
        border: 2px solid rgba(136, 219, 242, 0.3);
        border-radius: 14px;
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(136, 219, 242, 0.1);
    }

    .action-btn:hover {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(136, 219, 242, 0.35);
    }

    .action-btn i {
        font-size: 1.25rem;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    /* Staggered animation delays */
    .metric-card:nth-child(1) { animation-delay: 0.1s; }
    .metric-card:nth-child(2) { animation-delay: 0.2s; }
    .metric-card:nth-child(3) { animation-delay: 0.3s; }
    .metric-card:nth-child(4) { animation-delay: 0.4s; }

    .patient-card:nth-child(1) { animation-delay: 0.1s; }
    .patient-card:nth-child(2) { animation-delay: 0.2s; }
    .patient-card:nth-child(3) { animation-delay: 0.3s; }
    .patient-card:nth-child(4) { animation-delay: 0.4s; }
    .patient-card:nth-child(5) { animation-delay: 0.5s; }

    .patient-card {
        animation: fadeInUp 0.5s ease backwards;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .metrics-section,
        .featured-section {
            grid-column: span 1;
        }

        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        /* Page Header Mobile */
        .page-header {
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
        }

        .header-left {
            width: 100%;
        }

        .header-icon-title {
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
        }

        .header-icon i {
            font-size: 1.5rem;
        }

        .header-title-wrapper h1 {
            font-size: 1.375rem;
        }

        .header-subtitle {
            font-size: 0.8125rem;
        }

        .header-stats {
            width: 100%;
            flex-direction: column;
            gap: 0.625rem;
        }

        .stat-pill {
            width: 100%;
            padding: 0.875rem 1.125rem;
            justify-content: space-between;
        }

        .stat-pill i {
            font-size: 1rem;
        }

        .stat-pill-value {
            font-size: 1.25rem;
        }

        /* Dashboard Grid Mobile */
        .dashboard-grid {
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        /* Metrics Grid Mobile */
        .metrics-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .metric-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .metric-header {
            margin-bottom: 1rem;
        }

        .metric-label {
            font-size: 0.75rem;
        }

        .metric-badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.6875rem;
        }

        .metric-value {
            font-size: 2.25rem;
            margin-bottom: 0.625rem;
        }

        .metric-description {
            font-size: 0.875rem;
        }

        /* Featured Card Mobile */
        .featured-card {
            padding: 1.75rem;
            border-radius: 18px;
        }

        .featured-icon {
            width: 56px;
            height: 56px;
            margin-bottom: 1.25rem;
        }

        .featured-icon i {
            font-size: 1.75rem;
        }

        .featured-label {
            font-size: 0.8125rem;
            margin-bottom: 0.75rem;
        }

        .featured-value {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }

        .featured-description {
            font-size: 0.875rem;
        }

        /* Activity Section Mobile */
        .activity-section {
            padding: 1.5rem;
            border-radius: 18px;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }

        .section-title-wrapper h2 {
            font-size: 1.125rem;
        }

        .section-subtitle {
            font-size: 0.8125rem;
        }

        .section-action {
            padding: 0.625rem 1.25rem;
            font-size: 0.8125rem;
            width: 100%;
            justify-content: center;
        }

        /* Patient Cards Mobile */
        .patients-grid {
            gap: 1rem;
        }

        .patient-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.25rem;
            border-radius: 14px;
            gap: 1rem;
        }

        .patient-card:hover {
            transform: translateX(2px);
        }

        .patient-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.125rem;
        }

        .patient-name {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .patient-details {
            gap: 0.625rem;
        }

        .patient-detail {
            font-size: 0.75rem;
        }

        .patient-detail i {
            font-size: 0.6875rem;
        }

        .patient-actions {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .patient-badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.6875rem;
        }

        .view-patient-btn {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        /* Quick Actions Mobile */
        .quick-actions {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .action-btn {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
        }

        /* Empty State Mobile */
        .empty-state {
            padding: 3rem 1.5rem;
            border-radius: 16px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            font-size: 2rem;
            margin-bottom: 1.25rem;
        }

        .empty-state-title {
            font-size: 1.25rem;
        }

        .empty-state-text {
            font-size: 0.9375rem;
            margin-bottom: 1.25rem;
        }
    }

    /* Extra Small Devices */
    @media (max-width: 480px) {
        .page-header {
            padding: 1.25rem;
        }

        .header-icon {
            width: 44px;
            height: 44px;
        }

        .header-icon i {
            font-size: 1.25rem;
        }

        .header-title-wrapper h1 {
            font-size: 1.25rem;
        }

        .metric-value {
            font-size: 2rem;
        }

        .featured-value {
            font-size: 2.25rem;
        }

        .metric-card,
        .activity-section {
            padding: 1.25rem;
        }

        .patient-card {
            padding: 1rem;
        }

        .patient-name {
            font-size: 0.9375rem;
        }

        .patient-detail {
            font-size: 0.6875rem;
        }

        .section-title-wrapper h2 {
            font-size: 1rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <div class="header-left">
            <div class="header-icon-title">
                <div class="header-icon">
                    <i class="bi bi-grid-1x2"></i>
                </div>
                <div class="header-title-wrapper">
                    <h1>Panel de Coordinación</h1>
                    <div class="header-subtitle">Bienvenido, <?= htmlspecialchars(getCurrentUser()['nombre']) ?> • <?= date('d M Y') ?></div>
                </div>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-pill">
                <i class="bi bi-file-earmark-text"></i>
                <span class="stat-pill-value"><?= $total_archivos ?></span> archivos
            </div>
            <div class="stat-pill">
                <i class="bi bi-person-badge"></i>
                <span class="stat-pill-value"><?= $total_profesionales ?></span> profesionales
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Metrics Section (8 columns) -->
    <div class="metrics-section">
        <div class="metrics-grid">
            <!-- Pacientes Activos -->
            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-label">Pacientes Activos</span>
                    <span class="metric-badge active">
                        <i class="bi bi-check-circle"></i>
                        Activos
                    </span>
                </div>
                <div class="metric-value"><?= $pacientes_activos ?></div>
                <div class="metric-description">De <?= $total_pacientes ?> totales en el sistema</div>
            </div>

            <!-- Próximos a Vencer -->
            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-label">Próximos a vencer</span>
                    <span class="metric-badge urgent">
                        <i class="bi bi-exclamation-triangle"></i>
                        Urgente
                    </span>
                </div>
                <div class="metric-value"><?= $proximos_vencer ?></div>
                <div class="metric-description">Requieren atención inmediata</div>
            </div>

            <!-- Nuevos Este Mes -->
            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-label">Nuevos este mes</span>
                    <?php
                    $trend = $nuevos_pacientes_mes > 0 ? 'positive' : 'active';
                    ?>
                    <span class="metric-badge <?= $trend ?>">
                        <i class="bi bi-arrow-<?= $trend === 'positive' ? 'up' : 'right' ?>"></i>
                        <?= date('F') ?>
                    </span>
                </div>
                <div class="metric-value"><?= $nuevos_pacientes_mes ?></div>
                <div class="metric-description">Pacientes registrados este mes</div>
            </div>

            <!-- Prestaciones Vencidas -->
            <div class="metric-card">
                <div class="metric-header">
                    <span class="metric-label">Prestaciones Vencidas</span>
                    <span class="metric-badge warning">
                        <i class="bi bi-calendar-x"></i>
                        Atención
                    </span>
                </div>
                <div class="metric-value"><?= $prestaciones_vencidas ?></div>
                <div class="metric-description"><?= $vencen_esta_semana ?> vencen esta semana</div>
            </div>
        </div>
    </div>

    <!-- Featured Section (4 columns) -->
    <div class="featured-section">
        <div class="featured-card">
            <div class="featured-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="featured-label">Total Pacientes</div>
            <div class="featured-value"><?= $total_pacientes ?></div>
            <div class="featured-description">
                Pacientes registrados en el sistema
            </div>
        </div>
    </div>

    <!-- Activity Section (Full Width) -->
    <div class="activity-section">
        <div class="section-header">
            <div class="section-title-wrapper">
                <h2>Pacientes Recientes</h2>
                <div class="section-subtitle">Últimos registrados en el sistema</div>
            </div>
            <?php if (!empty($pacientes_recientes)): ?>
                <a href="<?= baseUrl('patients') ?>" class="section-action">
                    <i class="bi bi-people"></i>
                    Ver Todos
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($pacientes_recientes)): ?>
            <div class="patients-grid">
                <?php foreach ($pacientes_recientes as $paciente): ?>
                    <div class="patient-card">
                        <div class="patient-avatar">
                            <?= strtoupper(substr($paciente['nombre_completo'], 0, 2)) ?>
                        </div>
                        <div class="patient-info">
                            <div class="patient-name"><?= htmlspecialchars($paciente['nombre_completo']) ?></div>
                            <div class="patient-details">
                                <?php if ($paciente['profesional_nombre']): ?>
                                    <div class="patient-detail">
                                        <i class="bi bi-person-badge"></i>
                                        <?= htmlspecialchars($paciente['profesional_nombre']) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($paciente['empresa_nombre']): ?>
                                    <div class="patient-detail">
                                        <i class="bi bi-building"></i>
                                        <?= htmlspecialchars($paciente['empresa_nombre']) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($paciente['total_prestaciones'] > 0): ?>
                                    <div class="patient-detail">
                                        <i class="bi bi-heart-pulse"></i>
                                        <?= $paciente['total_prestaciones'] ?> prestación(es)
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="patient-actions">
                            <?php if ($paciente['paciente_recurrente']): ?>
                                <span class="patient-badge info">
                                    <i class="bi bi-arrow-repeat"></i> Recurrente
                                </span>
                            <?php elseif ($paciente['estado'] === 'activo'): ?>
                                <span class="patient-badge success">
                                    <i class="bi bi-check-circle"></i> Activo
                                </span>
                            <?php else: ?>
                                <span class="patient-badge warning">
                                    <i class="bi bi-clock"></i> Nuevo
                                </span>
                            <?php endif; ?>
                            <a href="<?= baseUrl('patients/view/' . $paciente['id']) ?>" class="view-patient-btn">
                                Ver <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="<?= baseUrl('patients/create') ?>" class="action-btn">
                    <i class="bi bi-plus-circle"></i>
                    Nuevo Paciente
                </a>
                <a href="<?= baseUrl('patients') ?>" class="action-btn">
                    <i class="bi bi-people"></i>
                    Ver Todos los Pacientes
                </a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="empty-state-title">No hay pacientes registrados</h3>
                <p class="empty-state-text">Comienza agregando tu primer paciente al sistema</p>
                <a href="<?= baseUrl('patients/create') ?>" class="action-btn" style="display: inline-flex; margin: 0 auto;">
                    <i class="bi bi-plus-circle"></i>
                    Agregar Primer Paciente
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
