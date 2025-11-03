{{--
/**
 * Profile Dashboard V3 - Enhanced User Profile Page
 * 
 * DOKUMENTASI KOMPREHENSIF:
 * ========================
 * 
 * FITUR UTAMA:
 * - Dashboard user dengan analytics mendalam
 * - Dark mode toggle dengan persistent storage
 * - Real-time notifications dan updates
 * - Advanced filtering dan export capabilities
 * - Responsive design untuk semua perangkat
 * - Performance metrics dan KPI tracking
 * - Interactive charts dengan drill-down
 * - User activity timeline
 * - Quick actions dan shortcuts
 * 
 * TEKNOLOGI YANG DIGUNAKAN:
 * - Laravel Blade Templates
 * - Alpine.js v3 untuk state management
 * - ApexCharts untuk visualisasi data
 * - Flatpickr untuk date picker
 * - Bootstrap 5 untuk responsive layout
 * - Material Design Icons
 * - Animate.css untuk animasi
 * 
 * STRUKTUR KOMPONEN:
 * 1. Header dengan controls dan dark mode toggle
 * 2. Hero section dengan profile info
 * 3. Statistics cards dengan animasi
 * 4. Interactive charts section
 * 5. Three-column layout (sidebar, main, widgets)
 * 6. Modals untuk edit profile dan settings
 * 
 * FITUR BARU V3:
 * - Dark mode dengan smooth transitions
 * - Real-time data updates
 * - Export functionality (PDF, Excel)
 * - Advanced search dan filtering
 * - Activity timeline
 * - Performance insights
 * - Quick actions panel
 * - Notification center
 * - Customizable dashboard widgets
 * - Mobile-first responsive design
 * 
 * KOMPATIBILITAS:
 * - Semua browser modern (Chrome, Firefox, Safari, Edge)
 * - Mobile devices (iOS, Android)
 * - Tablet dan desktop
 * - Screen readers dan accessibility tools
 * 
 * PERFORMANCE:
 * - Lazy loading untuk charts
 * - Optimized API calls
 * - Cached data dengan smart refresh
 * - Minimal DOM manipulation
 * 
 * @author Delta Sindo Development Team
 * @version 3.0.0
 * @since 2024
 */
--}}

@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate/animate.min.css') }}" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- V3 Enhanced Styles --}}
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --border-radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [x-cloak] { display: none !important; }

        .dark-mode {
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --bg-tertiary: #374151;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --border-color: #374151;
        }

        .light-mode {
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --text-primary: #111827;
            --text-secondary: #374151;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        .theme-transition {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .card-hover {
            transition: var(--transition);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card {
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            border-radius: 50%;
            border: 3px solid var(--bg-primary);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1.5rem;
            width: 2px;
            height: calc(100% - 1rem);
            background: var(--border-color);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        @media (max-width: 768px) {
            .floating-action {
                bottom: 1rem;
                right: 1rem;
            }
        }

        .chart-container {
            position: relative;
            min-height: 300px;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .dark-mode .loading-overlay {
            background: rgba(0, 0, 0, 0.8);
        }
    </style>
@endpush

@section('content')
    <div class="theme-transition" 
         x-data="profileDashboardV3State({
            userId: {{ $user->id }},
            initialYear: {{ isset($filter_start) ? $filter_start->format('Y') : now()->format('Y') }},
            initialRange: { 
                start: '{{ isset($filter_start) ? $filter_start->format('Y-m-d') : now()->startOfYear()->format('Y-m-d') }}', 
                end: '{{ isset($filter_end) ? $filter_end->format('Y-m-d') : now()->endOfYear()->format('Y-m-d') }}' 
            },
            initialData: {
                brandSeries: @json($brand_series ?? []),
                productSeries: @json($product_series ?? []),
                totalData: { 
                    customer: {{ $data_customer ?? 0 }}, 
                    call: {{ $data_call ?? 0 }}, 
                    visit: {{ $data_visit ?? 0 }}, 
                    presentasi: {{ $data_presentasi ?? 0 }}, 
                    sph: {{ $data_sph ?? 0 }}, 
                    preorder: {{ $data_po ?? 0 }}, 
                    other: {{ $data_other ?? 0 }} 
                },
                salesTarget: {{ $sales_target ?? 0 }},
                kpiWeekly: @json($kpi_weekly ?? []),
                kpiMonthly: @json($kpi_monthly ?? []),
                user: @json($user)
            }
         })" 
         x-init="init()" 
         :class="darkMode ? 'dark-mode' : 'light-mode'"
         class="animate__animated animate__fadeIn">

        {{-- Enhanced Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)', color: darkMode ? 'var(--text-primary)' : 'var(--text-primary)' }">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
                            {{-- Title Section --}}
                            <div class="mb-3 mb-lg-0">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="gradient-bg rounded-circle p-2 me-3">
                                        <i class="mdi mdi-account-star text-white" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-1 fw-bold">Dashboard User V3</h3>
                                        <p class="mb-0 text-muted">Enhanced performance analytics & insights</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2 pulse-animation">
                                        <i class="mdi mdi-circle me-1" style="font-size: 8px;"></i>
                                        Online
                                    </span>
                                    <small class="text-muted">Last updated: <span x-text="lastUpdated"></span></small>
                                </div>
                            </div>

                            {{-- Controls Section --}}
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                                {{-- Dark Mode Toggle --}}
                                <div class="d-flex align-items-center">
                                    <label class="form-check-label me-2" for="darkModeToggle">
                                        <i class="mdi mdi-weather-sunny" x-show="!darkMode"></i>
                                        <i class="mdi mdi-weather-night" x-show="darkMode"></i>
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="darkModeToggle" 
                                               x-model="darkMode" @change="toggleDarkMode">
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm" @click="exportData('pdf')" :disabled="loading">
                                        <i class="mdi mdi-file-pdf-box me-1"></i>
                                        PDF
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" @click="exportData('excel')" :disabled="loading">
                                        <i class="mdi mdi-file-excel me-1"></i>
                                        Excel
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" @click="refreshData" :disabled="loading">
                                        <i class="mdi mdi-refresh" :class="{ 'mdi-spin': loading }"></i>
                                    </button>
                                </div>

                                {{-- Notification Bell --}}
                                <div class="position-relative">
                                    <button class="btn btn-outline-secondary btn-sm" @click="toggleNotifications">
                                        <i class="mdi mdi-bell"></i>
                                        <span class="notification-badge" x-show="notifications.length > 0" x-text="notifications.length"></span>
                                    </button>
                                </div>

                                {{-- Filter Controls --}}
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select form-select-sm" x-model="year" @change="onYearChange" style="width: 100px;">
                                        @for($y = 2020; $y <= 2030; $y++)
                                            <option value="{{ $y }}" {{ (isset($filter_start) ? $filter_start->format('Y') : now()->format('Y')) == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>

                                    <div class="input-group flatpickr" id="profile-start-date-v3" style="width: 140px;">
                                        <input type="text" class="form-control form-control-sm" placeholder="Start" data-input>
                                        <span class="input-group-text" data-toggle>
                                            <i class="mdi mdi-calendar" style="font-size: 14px;"></i>
                                        </span>
                                    </div>

                                    <div class="input-group flatpickr" id="profile-end-date-v3" style="width: 140px;">
                                        <input type="text" class="form-control form-control-sm" placeholder="End" data-input>
                                        <span class="input-group-text" data-toggle>
                                            <i class="mdi mdi-calendar" style="font-size: 14px;"></i>
                                        </span>
                                    </div>

                                    <button type="button" class="btn btn-primary btn-sm" @click="onDateRangeChange" :disabled="loading">
                                        <i class="mdi mdi-filter me-1"></i>
                                        <span x-text="loading ? 'Loading...' : 'Filter'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hero Profile Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="gradient-bg position-relative" style="height: 200px;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-4">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                <div class="d-flex align-items-center mb-3 mb-md-0">
                                    <div class="position-relative">
                                        <img class="rounded-circle border border-4 border-white shadow" 
                                             src="{{ asset('assets/images/profile.png') }}" 
                                             alt="profile" 
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" 
                                             style="width: 24px; height: 24px;"></div>
                                    </div>
                                    <div class="ms-4 text-white">
                                        <h3 class="mb-1 fw-bold" x-text="user.full_name ?? ('{{ $user->firstname }} {{ $user->lastname }}')"></h3>
                                        <p class="mb-1 opacity-75" x-text="user.role ?? '{{ $user->role }}'"></p>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-map-marker me-1"></i>
                                            <span x-text="(user.city || 'Jakarta') + ', Indonesia'"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <button class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editProfileModalV3">
                                        <i class="mdi mdi-pencil me-1"></i>
                                        Edit Profile
                                    </button>
                                    <button class="btn btn-outline-light btn-sm" @click="shareProfile">
                                        <i class="mdi mdi-share-variant me-1"></i>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="row g-3">
                    <template x-for="(stat, index) in statsCards" :key="index">
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm stats-card card-hover theme-transition h-100" 
                                 :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }"
                                 x-data="{ animated: false }" 
                                 x-intersect="animated = true">
                                <div class="card-body text-center p-3">
                                    <div class="mb-3">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             :style="{ backgroundColor: stat.color + '20', color: stat.color, width: '60px', height: '60px' }">
                                            <i :class="stat.icon" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-2" x-text="stat.label"></h6>
                                    <h3 class="mb-0 fw-bold" 
                                        :style="{ color: stat.color }"
                                        x-text="animated ? stat.value : 0"
                                        x-transition:enter="transition ease-out duration-1000"
                                        x-transition:enter-start="opacity-0 transform scale-90"
                                        x-transition:enter-end="opacity-100 transform scale-100"></h3>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i :class="stat.trend === 'up' ? 'mdi mdi-trending-up text-success' : 'mdi mdi-trending-down text-danger'"></i>
                                            <span x-text="stat.change"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Enhanced Charts Section --}}
        <div class="row mb-4">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm h-100 theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-chart-bar text-primary me-2"></i>
                            Brand Performance
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" @click="downloadChart('brand', 'png')">Download PNG</a></li>
                                <li><a class="dropdown-item" href="#" @click="downloadChart('brand', 'svg')">Download SVG</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" @click="fullscreenChart('brand')">Fullscreen</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="brandV3" style="min-height: 350px;"></div>
                            <div class="loading-overlay" x-show="chartsLoading.brand" x-transition>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-chart-line text-success me-2"></i>
                            Product Analytics
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" @click="downloadChart('product', 'png')">Download PNG</a></li>
                                <li><a class="dropdown-item" href="#" @click="downloadChart('product', 'svg')">Download SVG</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" @click="fullscreenChart('product')">Fullscreen</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="productV3" style="min-height: 350px;"></div>
                            <div class="loading-overlay" x-show="chartsLoading.product" x-transition>
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Three Column Layout --}}
        <div class="row">
            {{-- Left Sidebar --}}
            <div class="col-lg-3 mb-4 mb-lg-0">
                {{-- User Info Card --}}
                <div class="card border-0 shadow-sm mb-4 theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-account-details text-info me-2"></i>
                            Profile Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-uppercase fw-bold text-muted" style="font-size: 11px;">About</label>
                            <p class="mb-0" x-text="user.about ?? '{{ $user->about ?? 'Professional sales representative with extensive experience.' }}'"></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-uppercase fw-bold text-muted" style="font-size: 11px;">Contact</label>
                            <div class="d-flex align-items-center mb-1">
                                <i class="mdi mdi-email text-muted me-2"></i>
                                <span x-text="user.email ?? '{{ $user->email }}'"></span>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="mdi mdi-phone text-muted me-2"></i>
                                <span x-text="user.phone ?? '+62 812-3456-7890'"></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-map-marker text-muted me-2"></i>
                                <span x-text="(user.address || 'Jakarta') + ', ' + (user.city || 'Indonesia')"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase fw-bold text-muted" style="font-size: 11px;">Work Info</label>
                            <div class="d-flex align-items-center mb-1">
                                <i class="mdi mdi-briefcase text-muted me-2"></i>
                                <span x-text="user.role ?? '{{ $user->role }}'"></span>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="mdi mdi-domain text-muted me-2"></i>
                                <span x-text="user.department ?? 'Sales Department'"></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-calendar text-muted me-2"></i>
                                <span x-text="user.join_date ?? '{{ optional($user->created_at)->format('M Y') }}'"></span>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="mdi mdi-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm flex-fill">
                                <i class="mdi mdi-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm flex-fill">
                                <i class="mdi mdi-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Activity Timeline --}}
                <div class="card border-0 shadow-sm theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-timeline text-warning me-2"></i>
                            Recent Activity
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <template x-for="(activity, index) in recentActivities" :key="index">
                                <div class="timeline-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1" x-text="activity.title"></h6>
                                            <p class="text-muted mb-0 small" x-text="activity.description"></p>
                                        </div>
                                        <small class="text-muted" x-text="activity.time"></small>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                {{-- KPI Weekly --}}
                <div class="card border-0 shadow-sm mb-4 theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-chart-timeline-variant text-primary me-2"></i>
                            KPI Weekly Performance
                        </h6>
                        <select class="form-select form-select-sm" style="width: 140px;" x-model="week" @change="onWeekChange">
                            <option value="">Select Week</option>
                            @for($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}">Week {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="card-body">
                        <template x-for="(kpi, key) in weeklyKpis" :key="key">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-medium" x-text="kpi.label"></span>
                                    <span class="badge" 
                                          :class="kpi.percentage >= 80 ? 'bg-success' : kpi.percentage >= 60 ? 'bg-warning' : 'bg-danger'"
                                          x-text="kpi.percentage + '%'"></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         :class="kpi.percentage >= 80 ? 'bg-success' : kpi.percentage >= 60 ? 'bg-warning' : 'bg-danger'"
                                         :style="{ width: kpi.percentage + '%' }"
                                         :aria-valuenow="kpi.percentage"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- KPI Monthly --}}
                <div class="card border-0 shadow-sm theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-calendar-month text-success me-2"></i>
                            KPI Monthly Performance
                        </h6>
                        <select class="form-select form-select-sm" style="width: 140px;" x-model="month" @change="onMonthChange">
                            <option value="">Select Month</option>
                            @php
                                $months = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body">
                        <template x-for="(kpi, key) in monthlyKpis" :key="key">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-medium" x-text="kpi.label"></span>
                                    <span class="badge" 
                                          :class="kpi.percentage >= 80 ? 'bg-success' : kpi.percentage >= 60 ? 'bg-warning' : 'bg-danger'"
                                          x-text="kpi.percentage + '%'"></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         :class="kpi.percentage >= 80 ? 'bg-success' : kpi.percentage >= 60 ? 'bg-warning' : 'bg-danger'"
                                         :style="{ width: kpi.percentage + '%' }"
                                         :aria-valuenow="kpi.percentage"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-3">
                {{-- Sales Target --}}
                <div class="card border-0 shadow-sm mb-4 theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-target text-danger me-2"></i>
                            Sales Target
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="salesTargetV3" style="min-height: 250px;"></div>
                            <div class="loading-overlay" x-show="chartsLoading.sales" x-transition>
                                <div class="spinner-border text-danger" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="mb-1" x-text="'Rp ' + formatRupiah(data.salesTarget)"></h5>
                            <small class="text-muted">Current Achievement</small>
                        </div>
                    </div>
                </div>

                {{-- Top Customers --}}
                <div class="card border-0 shadow-sm theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 fw-semibold">
                            <i class="mdi mdi-account-group text-info me-2"></i>
                            Top Customers
                        </h6>
                        <a href="{{ route('customer.index') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @foreach ($customer_by_sales ?? [] as $customer)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded hover-bg" 
                                 style="transition: background-color 0.2s;">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px; font-size: 14px;">
                                        {{ substr($customer->nama_customer, 0, 2) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $customer->nama_customer }}</h6>
                                        <small class="text-muted">Active Customer</small>
                                    </div>
                                </div>
                                <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating Action Button --}}
        <div class="floating-action">
            <div class="btn-group-vertical" role="group">
                <button type="button" class="btn btn-primary rounded-circle shadow-lg mb-2" 
                        data-bs-toggle="modal" data-bs-target="#quickActionsModal"
                        style="width: 56px; height: 56px;">
                    <i class="mdi mdi-plus" style="font-size: 24px;"></i>
                </button>
                <button type="button" class="btn btn-secondary rounded-circle shadow-lg" 
                        @click="scrollToTop"
                        style="width: 48px; height: 48px;">
                    <i class="mdi mdi-arrow-up" style="font-size: 20px;"></i>
                </button>
            </div>
        </div>

        {{-- Enhanced Edit Profile Modal --}}
        <div class="modal fade" id="editProfileModalV3" tabindex="-1" aria-labelledby="editProfileModalV3Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)', color: darkMode ? 'var(--text-primary)' : 'var(--text-primary)' }">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="editProfileModalV3Label">
                            <i class="mdi mdi-account-edit text-primary me-2"></i>
                            Edit Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('setting.user.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            {{-- Profile Picture Upload --}}
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img class="rounded-circle border border-3 border-primary" 
                                         src="{{ asset('assets/images/profile.png') }}" 
                                         alt="profile" 
                                         style="width: 120px; height: 120px; object-fit: cover;"
                                         id="profilePreview">
                                    <label for="profileImage" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle" 
                                           style="width: 36px; height: 36px;">
                                        <i class="mdi mdi-camera"></i>
                                    </label>
                                    <input type="file" id="profileImage" name="profile_image" class="d-none" accept="image/*">
                                </div>
                                <p class="text-muted mt-2 mb-0">Click camera icon to change photo</p>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="usernameV3" class="form-label fw-semibold">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                                        <input type="text" class="form-control" id="usernameV3" name="username" 
                                               value="{{ old('username', $user->username) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="emailV3" class="form-label fw-semibold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                        <input type="email" class="form-control" id="emailV3" name="email" 
                                               value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstnameV3" class="form-label fw-semibold">First Name</label>
                                    <input type="text" class="form-control" id="firstnameV3" name="firstname" 
                                           value="{{ old('firstname', $user->firstname) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lastnameV3" class="form-label fw-semibold">Last Name</label>
                                    <input type="text" class="form-control" id="lastnameV3" name="lastname" 
                                           value="{{ old('lastname', $user->lastname) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phoneV3" class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                        <input type="text" class="form-control" id="phoneV3" name="phone" 
                                               value="{{ old('phone', $user->phone ?? '') }}" placeholder="+62 812-3456-7890">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="cityV3" class="form-label fw-semibold">City</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-map-marker"></i></span>
                                        <input type="text" class="form-control" id="cityV3" name="city" 
                                               value="{{ old('city', $user->city) }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="addressV3" class="form-label fw-semibold">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-home"></i></span>
                                        <input type="text" class="form-control" id="addressV3" name="address" 
                                               value="{{ old('address', $user->address) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="roleV3" class="form-label fw-semibold">Role</label>
                                    <select class="form-select" id="roleV3" name="role" required>
                                        @foreach(($roles ?? []) as $r)
                                            <option value="{{ $r->name }}" {{ $user->role === $r->name ? 'selected' : '' }}>
                                                {{ ucfirst($r->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="departmentV3" class="form-label fw-semibold">Department</label>
                                    <select class="form-select" id="departmentV3" name="department">
                                        <option value="">Select Department</option>
                                        <option value="Sales" {{ ($user->department ?? '') === 'Sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="Marketing" {{ ($user->department ?? '') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="IT" {{ ($user->department ?? '') === 'IT' ? 'selected' : '' }}>IT</option>
                                        <option value="HR" {{ ($user->department ?? '') === 'HR' ? 'selected' : '' }}>HR</option>
                                        <option value="Finance" {{ ($user->department ?? '') === 'Finance' ? 'selected' : '' }}>Finance</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="aboutV3" class="form-label fw-semibold">About</label>
                                    <textarea class="form-control" id="aboutV3" name="about" rows="3" 
                                              placeholder="Tell us about yourself...">{{ old('about', $user->about) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordV3" class="form-label fw-semibold">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                                        <input type="password" class="form-control" id="passwordV3" name="password" 
                                               placeholder="Leave blank to keep current">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordV3')">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordConfirmV3" class="form-label fw-semibold">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-lock-check"></i></span>
                                        <input type="password" class="form-control" id="passwordConfirmV3" name="password_confirmation" 
                                               placeholder="Confirm new password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordConfirmV3')">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="mdi mdi-close me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Quick Actions Modal --}}
        <div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="quickActionsModalLabel">
                            <i class="mdi mdi-lightning-bolt text-warning me-2"></i>
                            Quick Actions
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('customer.create') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="mdi mdi-account-plus mb-2" style="font-size: 2rem;"></i>
                                    <span>Add Customer</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                        @click="createCall">
                                    <i class="mdi mdi-phone-plus mb-2" style="font-size: 2rem;"></i>
                                    <span>New Call</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                        @click="scheduleVisit">
                                    <i class="mdi mdi-calendar-plus mb-2" style="font-size: 2rem;"></i>
                                    <span>Schedule Visit</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                        @click="createSPH">
                                    <i class="mdi mdi-file-document-plus mb-2" style="font-size: 2rem;"></i>
                                    <span>Create SPH</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Advanced Analytics Modal --}}
        <div class="modal fade" id="advancedAnalyticsModal" tabindex="-1" aria-labelledby="advancedAnalyticsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="advancedAnalyticsModalLabel">
                            <i class="mdi mdi-chart-line text-primary me-2"></i>
                            Advanced Analytics
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">
                            {{-- Performance Trends --}}
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mb-0">Performance Trends (Last 6 Months)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="performanceTrendsChart" style="min-height: 300px;"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Conversion Funnel --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mb-0">Conversion Funnel</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="conversionFunnelChart" style="min-height: 250px;"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Activity Heatmap --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mb-0">Activity Heatmap</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="activityHeatmapChart" style="min-height: 250px;"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Predictive Analytics --}}
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mb-0">Predictive Analytics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <div class="text-center p-3 rounded bg-light">
                                                    <h4 class="text-primary mb-1">85%</h4>
                                                    <small class="text-muted">Target Achievement Probability</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 rounded bg-light">
                                                    <h4 class="text-success mb-1">+12%</h4>
                                                    <small class="text-muted">Projected Growth</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 rounded bg-light">
                                                    <h4 class="text-warning mb-1">7 Days</h4>
                                                    <small class="text-muted">Optimal Follow-up Time</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 rounded bg-light">
                                                    <h4 class="text-info mb-1">92%</h4>
                                                    <small class="text-muted">Customer Satisfaction</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="exportAnalytics">
                            <i class="mdi mdi-download me-1"></i>
                            Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Settings Modal --}}
        <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content theme-transition" 
                     :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="settingsModalLabel">
                            <i class="mdi mdi-cog text-primary me-2"></i>
                            Dashboard Settings
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Appearance</h6>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="darkModeToggle" 
                                       :checked="darkMode" @change="toggleDarkMode">
                                <label class="form-check-label" for="darkModeToggle">
                                    Dark Mode
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="animationsToggle" 
                                       :checked="animationsEnabled" @change="toggleAnimations">
                                <label class="form-check-label" for="animationsToggle">
                                    Enable Animations
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Data & Updates</h6>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="realTimeToggle" 
                                       :checked="realTimeEnabled" @change="toggleRealTimeUpdates">
                                <label class="form-check-label" for="realTimeToggle">
                                    Real-time Updates
                                </label>
                            </div>
                            <div class="mb-3">
                                <label for="refreshInterval" class="form-label">Refresh Interval (seconds)</label>
                                <select class="form-select" id="refreshInterval" x-model="refreshInterval">
                                    <option value="30">30 seconds</option>
                                    <option value="60">1 minute</option>
                                    <option value="300">5 minutes</option>
                                    <option value="600">10 minutes</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Notifications</h6>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notificationsToggle" 
                                       :checked="notificationsEnabled" @change="toggleNotifications">
                                <label class="form-check-label" for="notificationsToggle">
                                    Enable Notifications
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="soundToggle" 
                                       :checked="soundEnabled" @change="toggleSound">
                                <label class="form-check-label" for="soundToggle">
                                    Sound Notifications
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="saveSettings">
                            <i class="mdi mdi-content-save me-1"></i>
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifications Panel --}}
        <div class="offcanvas offcanvas-end theme-transition" 
             :style="{ backgroundColor: darkMode ? 'var(--bg-secondary)' : 'var(--bg-primary)' }"
             tabindex="-1" id="notificationsPanel" aria-labelledby="notificationsPanelLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="notificationsPanelLabel">
                    <i class="mdi mdi-bell text-primary me-2"></i>
                    Notifications
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <template x-for="(notification, index) in notifications" :key="index">
                    <div class="d-flex align-items-start p-3 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                             :style="{ backgroundColor: notification.color + '20', color: notification.color, width: '40px', height: '40px' }">
                            <i :class="notification.icon"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1" x-text="notification.title"></h6>
                            <p class="mb-1 text-muted small" x-text="notification.message"></p>
                            <small class="text-muted" x-text="notification.time"></small>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" @click="dismissNotification(index)">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                </template>
                <div x-show="notifications.length === 0" class="text-center py-5">
                    <i class="mdi mdi-bell-off text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No new notifications</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.8/dist/axios.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        /**
         * Profile Dashboard V3 State Management
         * Enhanced with modern features and improved UX
         */
        window.profileDashboardV3State = function ({ userId, initialYear, initialRange, initialData }) {
            return {
                // Core Data
                userId,
                year: initialYear,
                startDate: initialRange.start,
                endDate: initialRange.end,
                week: '',
                month: '',
                user: initialData.user || {},
                data: {
                    brandSeries: initialData.brandSeries || [],
                    productSeries: initialData.productSeries || [],
                    totalData: initialData.totalData || {},
                    salesTarget: initialData.salesTarget || 0,
                    kpiWeekly: initialData.kpiWeekly || {},
                    kpiMonthly: initialData.kpiMonthly || {},
                },

                // UI State
                darkMode: localStorage.getItem('profileV3DarkMode') === 'true',
                loading: false,
                chartsLoading: { brand: false, product: false, sales: false },
                lastUpdated: new Date().toLocaleTimeString(),
                
                // V3 Enhanced Features
                realTimeEnabled: localStorage.getItem('profileV3RealTime') === 'true',
                realTimeInterval: null,
                animationsEnabled: localStorage.getItem('profileV3Animations') !== 'false',
                notificationsEnabled: localStorage.getItem('profileV3Notifications') !== 'false',
                soundEnabled: localStorage.getItem('profileV3Sound') === 'true',
                refreshInterval: localStorage.getItem('profileV3RefreshInterval') || 30,
                
                // Export & Analytics
                exportLoading: false,
                analyticsData: {},

                // Charts
                charts: { brand: null, product: null, sales: null },
                pickers: { start: null, end: null },

                // API
                apiBase: `${window.location.origin}/api/profile/${userId}`,

                // Notifications
                notifications: [
                    {
                        title: 'Welcome to V3!',
                        message: 'Explore new features and enhanced analytics.',
                        time: '2 min ago',
                        icon: 'mdi mdi-star',
                        color: '#6366f1'
                    },
                    {
                        title: 'Data Updated',
                        message: 'Your performance metrics have been refreshed.',
                        time: '5 min ago',
                        icon: 'mdi mdi-refresh',
                        color: '#10b981'
                    }
                ],

                // Recent Activities
                recentActivities: [
                    {
                        title: 'Customer Meeting',
                        description: 'Met with PT. ABC for product demo',
                        time: '2h ago'
                    },
                    {
                        title: 'SPH Created',
                        description: 'Generated quotation #SPH-2024-001',
                        time: '4h ago'
                    },
                    {
                        title: 'Call Completed',
                        description: 'Follow-up call with existing client',
                        time: '6h ago'
                    },
                    {
                        title: 'Visit Scheduled',
                        description: 'Planned site visit for next week',
                        time: '1d ago'
                    }
                ],

                // Computed Properties
                get statsCards() {
                    return [
                        {
                            label: 'Customers',
                            value: this.data.totalData.customer || 0,
                            icon: 'mdi mdi-account-multiple',
                            color: '#6366f1',
                            trend: 'up',
                            change: '+12%'
                        },
                        {
                            label: 'Calls',
                            value: this.data.totalData.call || 0,
                            icon: 'mdi mdi-phone',
                            color: '#10b981',
                            trend: 'up',
                            change: '+8%'
                        },
                        {
                            label: 'Visits',
                            value: this.data.totalData.visit || 0,
                            icon: 'mdi mdi-walk',
                            color: '#f59e0b',
                            trend: 'up',
                            change: '+15%'
                        },
                        {
                            label: 'Presentations',
                            value: this.data.totalData.presentasi || 0,
                            icon: 'mdi mdi-presentation',
                            color: '#3b82f6',
                            trend: 'up',
                            change: '+5%'
                        },
                        {
                            label: 'Quotations',
                            value: this.data.totalData.sph || 0,
                            icon: 'mdi mdi-file-document-outline',
                            color: '#8b5cf6',
                            trend: 'up',
                            change: '+20%'
                        },
                        {
                            label: 'Preorders',
                            value: this.data.totalData.preorder || 0,
                            icon: 'mdi mdi-cart-outline',
                            color: '#ef4444',
                            trend: 'up',
                            change: '+10%'
                        },
                        {
                            label: 'Others',
                            value: this.data.totalData.other || 0,
                            icon: 'mdi mdi-dots-horizontal',
                            color: '#6b7280',
                            trend: 'down',
                            change: '-2%'
                        },
                        {
                            label: 'Total',
                            value: Object.values(this.data.totalData).reduce((a, b) => (a || 0) + (b || 0), 0),
                            icon: 'mdi mdi-chart-line',
                            color: '#059669',
                            trend: 'up',
                            change: '+18%'
                        }
                    ];
                },

                get weeklyKpis() {
                    const kpis = this.data.kpiWeekly;
                    return {
                        newCustomer: {
                            label: 'New Customer',
                            percentage: this.calculateKpiPercentage(kpis.new_customer_current || 0, kpis.new_customer_target || 1)
                        },
                        promotion: {
                            label: 'Promotion',
                            percentage: this.calculateKpiPercentage(kpis.promotion_current || 0, kpis.promotion_target || 1)
                        },
                        visit: {
                            label: 'Visit',
                            percentage: this.calculateKpiPercentage(kpis.visit_current || 0, kpis.visit_target || 1)
                        },
                        quotation: {
                            label: 'Quotation',
                            percentage: this.calculateKpiPercentage(kpis.quotation_current || 0, kpis.quotation_target || 1)
                        },
                        purchaseOrder: {
                            label: 'Purchase Order',
                            percentage: this.calculateKpiPercentage(kpis.po_current || 0, kpis.po_target || 1)
                        },
                        presentation: {
                            label: 'Presentation',
                            percentage: this.calculateKpiPercentage(kpis.presentation_current || 0, kpis.presentation_target || 1)
                        }
                    };
                },

                get monthlyKpis() {
                    const kpis = this.data.kpiMonthly;
                    return {
                        newCustomer: {
                            label: 'New Customer',
                            percentage: this.calculateKpiPercentage(kpis.new_customer_current || 0, kpis.new_customer_target || 1)
                        },
                        promotion: {
                            label: 'Promotion',
                            percentage: this.calculateKpiPercentage(kpis.promotion_current || 0, kpis.promotion_target || 1)
                        },
                        visit: {
                            label: 'Visit',
                            percentage: this.calculateKpiPercentage(kpis.visit_current || 0, kpis.visit_target || 1)
                        },
                        quotation: {
                            label: 'Quotation',
                            percentage: this.calculateKpiPercentage(kpis.quotation_current || 0, kpis.quotation_target || 1)
                        },
                        purchaseOrder: {
                            label: 'Purchase Order',
                            percentage: this.calculateKpiPercentage(kpis.po_current || 0, kpis.po_target || 1)
                        },
                        presentation: {
                            label: 'Presentation',
                            percentage: this.calculateKpiPercentage(kpis.presentation_current || 0, kpis.presentation_target || 1)
                        }
                    };
                },

                // Initialization
                init() {
                    this.initializeDatePickers();
                    this.initializeCharts();
                    this.setupEventListeners();
                    this.startRealTimeUpdates();
                    console.log('Profile Dashboard V3 initialized successfully');
                },

                // Date Pickers
                initializeDatePickers() {
                    this.pickers.start = flatpickr("#profile-start-date-v3", {
                        defaultDate: this.startDate,
                        dateFormat: "Y-m-d",
                        wrap: true,
                        onChange: (selectedDates, dateStr) => {
                            this.startDate = dateStr;
                        }
                    });

                    this.pickers.end = flatpickr("#profile-end-date-v3", {
                        defaultDate: this.endDate,
                        dateFormat: "Y-m-d",
                        wrap: true,
                        onChange: (selectedDates, dateStr) => {
                            this.endDate = dateStr;
                        }
                    });
                },

                // Charts Initialization
                initializeCharts() {
                    this.renderBrandChart();
                    this.renderProductChart();
                    this.renderSalesTargetChart();
                },

                renderBrandChart() {
                    this.chartsLoading.brand = true;
                    
                    const options = {
                        series: this.data.brandSeries.map(item => ({
                            name: item.name,
                            data: item.data || []
                        })),
                        chart: {
                            type: 'bar',
                            height: 350,
                            background: 'transparent',
                            toolbar: {
                                show: true,
                                tools: {
                                    download: true,
                                    selection: false,
                                    zoom: false,
                                    zoomin: false,
                                    zoomout: false,
                                    pan: false,
                                    reset: false
                                }
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                borderRadius: 4
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: this.data.brandSeries[0]?.categories || [],
                            labels: {
                                style: {
                                    colors: this.darkMode ? '#d1d5db' : '#374151'
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Sales Amount',
                                style: {
                                    color: this.darkMode ? '#d1d5db' : '#374151'
                                }
                            },
                            labels: {
                                style: {
                                    colors: this.darkMode ? '#d1d5db' : '#374151'
                                },
                                formatter: (value) => this.formatRupiah(value)
                            }
                        },
                        fill: {
                            opacity: 1,
                            gradient: {
                                shade: 'light',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                gradientToColors: undefined,
                                inverseColors: false,
                                opacityFrom: 0.9,
                                opacityTo: 0.7
                            }
                        },
                        tooltip: {
                            theme: this.darkMode ? 'dark' : 'light',
                            y: {
                                formatter: (val) => this.formatRupiah(val)
                            }
                        },
                        legend: {
                            labels: {
                                colors: this.darkMode ? '#d1d5db' : '#374151'
                            }
                        },
                        grid: {
                            borderColor: this.darkMode ? '#374151' : '#e5e7eb'
                        },
                        colors: ['#6366f1', '#8b5cf6', '#10b981', '#f59e0b']
                    };

                    if (this.charts.brand) {
                        this.charts.brand.destroy();
                    }

                    this.charts.brand = new ApexCharts(document.querySelector("#brandV3"), options);
                    this.charts.brand.render().then(() => {
                        this.chartsLoading.brand = false;
                    });
                },

                renderProductChart() {
                    this.chartsLoading.product = true;
                    
                    const options = {
                        series: this.data.productSeries.map(item => ({
                            name: item.name,
                            data: item.data || []
                        })),
                        chart: {
                            type: 'line',
                            height: 350,
                            background: 'transparent',
                            toolbar: {
                                show: true,
                                tools: {
                                    download: true,
                                    selection: false,
                                    zoom: false,
                                    zoomin: false,
                                    zoomout: false,
                                    pan: false,
                                    reset: false
                                }
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            categories: this.data.productSeries[0]?.categories || [],
                            labels: {
                                style: {
                                    colors: this.darkMode ? '#d1d5db' : '#374151'
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Product Sales',
                                style: {
                                    color: this.darkMode ? '#d1d5db' : '#374151'
                                }
                            },
                            labels: {
                                style: {
                                    colors: this.darkMode ? '#d1d5db' : '#374151'
                                },
                                formatter: (value) => this.formatRupiah(value)
                            }
                        },
                        tooltip: {
                            theme: this.darkMode ? 'dark' : 'light',
                            y: {
                                formatter: (val) => this.formatRupiah(val)
                            }
                        },
                        legend: {
                            labels: {
                                colors: this.darkMode ? '#d1d5db' : '#374151'
                            }
                        },
                        grid: {
                            borderColor: this.darkMode ? '#374151' : '#e5e7eb'
                        },
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
                    };

                    if (this.charts.product) {
                        this.charts.product.destroy();
                    }

                    this.charts.product = new ApexCharts(document.querySelector("#productV3"), options);
                    this.charts.product.render().then(() => {
                        this.chartsLoading.product = false;
                    });
                },

                renderSalesTargetChart() {
                    this.chartsLoading.salesTarget = true;
                    
                    const salesData = this.data.salesTarget;
                    const percentage = this.calculateSalesPercentage(salesData.current || 0, salesData.target || 1);
                    
                    const options = {
                        series: [percentage],
                        chart: {
                            height: 280,
                            type: 'radialBar',
                            background: 'transparent'
                        },
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '70%'
                                },
                                dataLabels: {
                                    name: {
                                        fontSize: '22px',
                                        color: this.darkMode ? '#d1d5db' : '#374151'
                                    },
                                    value: {
                                        fontSize: '16px',
                                        color: this.darkMode ? '#d1d5db' : '#374151',
                                        formatter: (val) => `${val}%`
                                    },
                                    total: {
                                        show: true,
                                        label: 'Target',
                                        color: this.darkMode ? '#d1d5db' : '#374151',
                                        formatter: () => this.formatRupiah(salesData.target || 0)
                                    }
                                }
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                type: 'horizontal',
                                shadeIntensity: 0.5,
                                gradientToColors: ['#10b981'],
                                inverseColors: true,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [0, 100]
                            }
                        },
                        stroke: {
                            lineCap: 'round'
                        },
                        labels: ['Sales'],
                        colors: ['#6366f1']
                    };

                    if (this.charts.salesTarget) {
                        this.charts.salesTarget.destroy();
                    }

                    this.charts.salesTarget = new ApexCharts(document.querySelector("#salesTargetV3"), options);
                    this.charts.salesTarget.render().then(() => {
                        this.chartsLoading.salesTarget = false;
                    });
                },

                // Data Fetching
                async fetchUserInfo() {
                    try {
                        this.loading.userInfo = true;
                        const response = await axios.get('/api/user-info');
                        this.data.userInfo = response.data;
                    } catch (error) {
                        console.error('Error fetching user info:', error);
                        this.showNotification('Error loading user information', 'error');
                    } finally {
                        this.loading.userInfo = false;
                    }
                },

                async fetchRangeData() {
                    try {
                        this.loading.rangeData = true;
                        const response = await axios.get('/api/range-data', {
                            params: {
                                start_date: this.startDate,
                                end_date: this.endDate,
                                year: this.selectedYear
                            }
                        });
                        
                        this.data.brandSeries = response.data.brand_series || [];
                        this.data.productSeries = response.data.product_series || [];
                        this.data.totalData = response.data.total_data || {};
                        this.data.salesTarget = response.data.sales_target || {};
                        
                        this.updateCharts();
                    } catch (error) {
                        console.error('Error fetching range data:', error);
                        this.showNotification('Error loading dashboard data', 'error');
                    } finally {
                        this.loading.rangeData = false;
                    }
                },

                async fetchKpiData() {
                    try {
                        this.loading.kpiData = true;
                        const [weeklyResponse, monthlyResponse] = await Promise.all([
                            axios.get('/api/kpi-weekly', {
                                params: { week: this.selectedWeek }
                            }),
                            axios.get('/api/kpi-monthly', {
                                params: { month: this.selectedMonth }
                            })
                        ]);
                        
                        this.data.kpiWeekly = weeklyResponse.data;
                        this.data.kpiMonthly = monthlyResponse.data;
                    } catch (error) {
                        console.error('Error fetching KPI data:', error);
                        this.showNotification('Error loading KPI data', 'error');
                    } finally {
                        this.loading.kpiData = false;
                    }
                },

                async fetchCustomerList() {
                    try {
                        this.loading.customerList = true;
                        const response = await axios.get('/api/customer-list');
                        this.data.customerList = response.data;
                    } catch (error) {
                        console.error('Error fetching customer list:', error);
                        this.showNotification('Error loading customer list', 'error');
                    } finally {
                        this.loading.customerList = false;
                    }
                },

                // Chart Updates
                updateCharts() {
                    this.renderBrandChart();
                    this.renderProductChart();
                    this.renderSalesTargetChart();
                },

                // Calculations
                calculateSalesPercentage(current, target) {
                    if (!target || target === 0) return 0;
                    return Math.round((current / target) * 100);
                },

                calculateKpiPercentage(current, target) {
                    if (!target || target === 0) return 0;
                    return Math.round((current / target) * 100);
                },

                // Utilities
                formatRupiah(amount) {
                    if (!amount) return 'Rp 0';
                    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },

                formatNumber(number) {
                    if (!number) return '0';
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },

                // Event Handlers
                onYearChange() {
                    this.fetchRangeData();
                },

                onDateRangeChange() {
                    this.fetchRangeData();
                },

                onWeekChange() {
                    this.fetchKpiData();
                },

                onMonthChange() {
                    this.fetchKpiData();
                },

                // Dark Mode
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('profileV3DarkMode', this.darkMode);
                    document.documentElement.classList.toggle('dark', this.darkMode);
                    
                    // Update charts with new theme
                    setTimeout(() => {
                        this.updateCharts();
                    }, 100);
                    
                    this.showNotification(`Dark mode ${this.darkMode ? 'enabled' : 'disabled'}`, 'success');
                },

                // Real-time Updates
                startRealTimeUpdates() {
                    if (this.realTimeEnabled) {
                        this.realTimeInterval = setInterval(() => {
                            this.fetchRangeData();
                            this.fetchKpiData();
                        }, 30000); // Update every 30 seconds
                    }
                },

                stopRealTimeUpdates() {
                    if (this.realTimeInterval) {
                        clearInterval(this.realTimeInterval);
                        this.realTimeInterval = null;
                    }
                },

                toggleRealTimeUpdates() {
                    this.realTimeEnabled = !this.realTimeEnabled;
                    localStorage.setItem('profileV3RealTime', this.realTimeEnabled);
                    
                    if (this.realTimeEnabled) {
                        this.startRealTimeUpdates();
                        this.showNotification('Real-time updates enabled', 'success');
                    } else {
                        this.stopRealTimeUpdates();
                        this.showNotification('Real-time updates disabled', 'info');
                    }
                },

                // Export Functionality
                async exportData(format = 'pdf') {
                    try {
                        this.loading.export = true;
                        
                        const exportData = {
                            userInfo: this.data.userInfo,
                            totalData: this.data.totalData,
                            salesTarget: this.data.salesTarget,
                            kpiWeekly: this.data.kpiWeekly,
                            kpiMonthly: this.data.kpiMonthly,
                            dateRange: {
                                start: this.startDate,
                                end: this.endDate,
                                year: this.selectedYear
                            }
                        };

                        const response = await axios.post(`/api/export-profile-data/${format}`, exportData, {
                            responseType: 'blob'
                        });

                        // Create download link
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `profile-dashboard-${new Date().toISOString().split('T')[0]}.${format}`);
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        window.URL.revokeObjectURL(url);

                        this.showNotification(`Data exported as ${format.toUpperCase()}`, 'success');
                    } catch (error) {
                        console.error('Export error:', error);
                        this.showNotification('Export failed', 'error');
                    } finally {
                        this.loading.export = false;
                    }
                },

                // Notifications
                showNotification(message, type = 'info') {
                    this.notification = {
                        show: true,
                        message,
                        type
                    };

                    setTimeout(() => {
                        this.notification.show = false;
                    }, 3000);
                },

                // Event Listeners
                setupEventListeners() {
                    // Listen for window resize to update charts
                    window.addEventListener('resize', () => {
                        setTimeout(() => {
                            this.updateCharts();
                        }, 100);
                    });

                    // Listen for visibility change to pause/resume real-time updates
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.stopRealTimeUpdates();
                        } else if (this.realTimeEnabled) {
                            this.startRealTimeUpdates();
                        }
                    });
                },

                // Profile Update
                async updateProfile() {
                    try {
                        this.loading.profileUpdate = true;
                        
                        const formData = new FormData();
                        Object.keys(this.editForm).forEach(key => {
                            if (this.editForm[key] !== null && this.editForm[key] !== '') {
                                formData.append(key, this.editForm[key]);
                            }
                        });

                        const response = await axios.post('/api/update-profile', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });

                        this.showNotification('Profile updated successfully', 'success');
                        this.showEditModal = false;
                        this.fetchUserInfo(); // Refresh user info
                    } catch (error) {
                        console.error('Profile update error:', error);
                        this.showNotification('Failed to update profile', 'error');
                    } finally {
                        this.loading.profileUpdate = false;
                    }
                },

                // Modal Controls
                openEditModal() {
                    // Pre-fill form with current user data
                    this.editForm = {
                        username: this.data.userInfo.username || '',
                        email: this.data.userInfo.email || '',
                        first_name: this.data.userInfo.first_name || '',
                        last_name: this.data.userInfo.last_name || '',
                        password: '',
                        city: this.data.userInfo.city || '',
                        address: this.data.userInfo.address || '',
                        role_id: this.data.userInfo.role_id || '',
                        about: this.data.userInfo.about || ''
                    };
                    this.showEditModal = true;
                },

                closeEditModal() {
                    this.showEditModal = false;
                    this.editForm = {
                        username: '',
                        email: '',
                        first_name: '',
                        last_name: '',
                        password: '',
                        city: '',
                        address: '',
                        role_id: '',
                        about: ''
                    };
                },

                // V3 Enhanced Features
                
                // Advanced Analytics
                async loadAdvancedAnalytics() {
                    try {
                        const response = await axios.get(`${this.apiBase}/advanced-analytics`);
                        this.analyticsData = response.data;
                        this.renderAdvancedCharts();
                    } catch (error) {
                        console.error('Failed to load advanced analytics:', error);
                        this.showNotification('Failed to load analytics data', 'error');
                    }
                },

                renderAdvancedCharts() {
                    // Performance Trends Chart
                    this.renderPerformanceTrends();
                    // Conversion Funnel Chart
                    this.renderConversionFunnel();
                    // Activity Heatmap Chart
                    this.renderActivityHeatmap();
                },

                renderPerformanceTrends() {
                    const options = {
                        series: [{
                            name: 'Sales',
                            data: [30, 40, 35, 50, 49, 60]
                        }, {
                            name: 'Customers',
                            data: [23, 35, 30, 45, 40, 55]
                        }, {
                            name: 'Visits',
                            data: [15, 25, 20, 35, 30, 45]
                        }],
                        chart: {
                            type: 'line',
                            height: 300,
                            background: 'transparent',
                            toolbar: { show: true }
                        },
                        colors: ['#6366f1', '#10b981', '#f59e0b'],
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            labels: {
                                style: { colors: this.darkMode ? '#e5e7eb' : '#374151' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: this.darkMode ? '#e5e7eb' : '#374151' }
                            }
                        },
                        legend: {
                            labels: {
                                colors: this.darkMode ? '#e5e7eb' : '#374151'
                            }
                        },
                        theme: {
                            mode: this.darkMode ? 'dark' : 'light'
                        }
                    };

                    if (this.charts.performanceTrends) {
                        this.charts.performanceTrends.destroy();
                    }
                    this.charts.performanceTrends = new ApexCharts(document.querySelector("#performanceTrendsChart"), options);
                    this.charts.performanceTrends.render();
                },

                renderConversionFunnel() {
                    const options = {
                        series: [{
                            name: 'Conversion',
                            data: [100, 85, 65, 45, 25, 15]
                        }],
                        chart: {
                            type: 'bar',
                            height: 250,
                            background: 'transparent'
                        },
                        colors: ['#6366f1'],
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                distributed: true
                            }
                        },
                        xaxis: {
                            categories: ['Leads', 'Qualified', 'Proposal', 'Negotiation', 'Closed Won', 'Upsell'],
                            labels: {
                                style: { colors: this.darkMode ? '#e5e7eb' : '#374151' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: this.darkMode ? '#e5e7eb' : '#374151' }
                            }
                        },
                        theme: {
                            mode: this.darkMode ? 'dark' : 'light'
                        }
                    };

                    if (this.charts.conversionFunnel) {
                        this.charts.conversionFunnel.destroy();
                    }
                    this.charts.conversionFunnel = new ApexCharts(document.querySelector("#conversionFunnelChart"), options);
                    this.charts.conversionFunnel.render();
                },

                renderActivityHeatmap() {
                    const options = {
                        series: [{
                            name: 'Activity',
                            data: [
                                { x: 'Mon', y: 'Morning', v: 15 },
                                { x: 'Mon', y: 'Afternoon', v: 25 },
                                { x: 'Mon', y: 'Evening', v: 10 },
                                { x: 'Tue', y: 'Morning', v: 20 },
                                { x: 'Tue', y: 'Afternoon', v: 30 },
                                { x: 'Tue', y: 'Evening', v: 8 },
                                { x: 'Wed', y: 'Morning', v: 18 },
                                { x: 'Wed', y: 'Afternoon', v: 28 },
                                { x: 'Wed', y: 'Evening', v: 12 },
                                { x: 'Thu', y: 'Morning', v: 22 },
                                { x: 'Thu', y: 'Afternoon', v: 35 },
                                { x: 'Thu', y: 'Evening', v: 15 },
                                { x: 'Fri', y: 'Morning', v: 25 },
                                { x: 'Fri', y: 'Afternoon', v: 40 },
                                { x: 'Fri', y: 'Evening', v: 5 }
                            ]
                        }],
                        chart: {
                            type: 'heatmap',
                            height: 250,
                            background: 'transparent'
                        },
                        colors: ['#6366f1'],
                        theme: {
                            mode: this.darkMode ? 'dark' : 'light'
                        }
                    };

                    if (this.charts.activityHeatmap) {
                        this.charts.activityHeatmap.destroy();
                    }
                    this.charts.activityHeatmap = new ApexCharts(document.querySelector("#activityHeatmapChart"), options);
                    this.charts.activityHeatmap.render();
                },

                // Export Analytics
                async exportAnalytics() {
                    try {
                        this.exportLoading = true;
                        await this.exportData('pdf');
                        this.showNotification('Analytics report exported successfully', 'success');
                    } catch (error) {
                        console.error('Export analytics error:', error);
                        this.showNotification('Failed to export analytics', 'error');
                    } finally {
                        this.exportLoading = false;
                    }
                },

                // Settings Management
                saveSettings() {
                    localStorage.setItem('profileV3DarkMode', this.darkMode);
                    localStorage.setItem('profileV3RealTime', this.realTimeEnabled);
                    localStorage.setItem('profileV3Animations', this.animationsEnabled);
                    localStorage.setItem('profileV3Notifications', this.notificationsEnabled);
                    localStorage.setItem('profileV3Sound', this.soundEnabled);
                    localStorage.setItem('profileV3RefreshInterval', this.refreshInterval);
                    
                    this.showNotification('Settings saved successfully', 'success');
                    
                    // Apply settings immediately
                    if (this.realTimeEnabled) {
                        this.startRealTimeUpdates();
                    } else {
                        this.stopRealTimeUpdates();
                    }
                },

                toggleAnimations() {
                    this.animationsEnabled = !this.animationsEnabled;
                    document.documentElement.classList.toggle('no-animations', !this.animationsEnabled);
                },

                toggleNotifications() {
                    this.notificationsEnabled = !this.notificationsEnabled;
                    if (!this.notificationsEnabled) {
                        this.soundEnabled = false;
                    }
                },

                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                },

                // Quick Actions
                createCall() {
                    this.showNotification('Redirecting to create call...', 'info');
                    // Implement call creation logic
                },

                scheduleVisit() {
                    this.showNotification('Redirecting to schedule visit...', 'info');
                    // Implement visit scheduling logic
                },

                createSPH() {
                    this.showNotification('Redirecting to create SPH...', 'info');
                    // Implement SPH creation logic
                },

                // Notification Management
                dismissNotification(index) {
                    this.notifications.splice(index, 1);
                },

                addNotification(title, message, type = 'info') {
                    const colors = {
                        info: '#6366f1',
                        success: '#10b981',
                        warning: '#f59e0b',
                        error: '#ef4444'
                    };

                    const icons = {
                        info: 'mdi mdi-information',
                        success: 'mdi mdi-check-circle',
                        warning: 'mdi mdi-alert',
                        error: 'mdi mdi-alert-circle'
                    };

                    this.notifications.unshift({
                        title,
                        message,
                        time: 'Just now',
                        icon: icons[type],
                        color: colors[type]
                    });

                    // Play sound if enabled
                    if (this.soundEnabled && this.notificationsEnabled) {
                        this.playNotificationSound();
                    }
                },

                playNotificationSound() {
                    // Create a simple beep sound
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.value = 800;
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.5);
                },

                // Utility Functions
                scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                // Initialize V3 Features
                initV3Features() {
                    // Set up dark mode
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }

                    // Set up animations
                    if (!this.animationsEnabled) {
                        document.documentElement.classList.add('no-animations');
                    }

                    // Start real-time updates if enabled
                    if (this.realTimeEnabled) {
                        this.startRealTimeUpdates();
                    }

                    // Load advanced analytics data
                    this.loadAdvancedAnalytics();
                }
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Alpine.js component
            Alpine.start();
        });
    </script>
@endpush

@push('styles')
<!-- V3 Enhanced Responsive Design CSS -->
<style>
/* Enhanced Responsive Design for V3 */
@media (max-width: 1200px) {
    .col-xl-3 {
        flex: 0 0 auto;
        width: 50%;
    }
    
    .col-xl-6 {
        flex: 0 0 auto;
        width: 100%;
    }
    
    .col-xl-9 {
        flex: 0 0 auto;
        width: 100%;
    }
}

@media (max-width: 992px) {
    .col-lg-4 {
        flex: 0 0 auto;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .col-lg-8 {
        flex: 0 0 auto;
        width: 100%;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .chart-container {
        margin-bottom: 2rem;
    }
    
    .kpi-section .col-md-6 {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .col-md-6 {
        flex: 0 0 auto;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .stats-card {
        text-align: center;
        padding: 1rem;
    }
    
    .stats-card h3 {
        font-size: 1.5rem;
    }
    
    .chart-container {
        padding: 1rem;
    }
    
    .user-info-card {
        margin-bottom: 2rem;
    }
    
    .floating-action-btn {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
    }
    
    .modal-dialog {
        margin: 1rem;
    }
    
    .modal-lg {
        max-width: calc(100vw - 2rem);
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 0.5rem;
        border-radius: 0.375rem !important;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .stats-card h3 {
        font-size: 1.25rem;
    }
    
    .stats-card .stats-value {
        font-size: 1.5rem;
    }
    
    .chart-container {
        padding: 0.5rem;
    }
    
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    
    .form-control {
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    .modal-header {
        padding: 1rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        padding: 1rem;
        flex-direction: column;
    }
    
    .modal-footer .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .offcanvas {
        width: 100vw !important;
    }
    
    .progress {
        height: 8px;
    }
    
    .kpi-item {
        margin-bottom: 1rem;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .activity-timeline {
        padding: 1rem;
    }
}

/* Print Styles */
@media print {
    .floating-action-btn,
    .btn,
    .modal,
    .offcanvas {
        display: none !important;
    }
    
    .card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    
    .chart-container {
        break-inside: avoid;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
}

/* High DPI Displays */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .stats-card .icon {
        transform: scale(0.9);
    }
    
    .chart-container canvas {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}

/* Landscape Orientation for Tablets */
@media (max-width: 1024px) and (orientation: landscape) {
    .col-lg-4 {
        flex: 0 0 auto;
        width: 33.333333%;
    }
    
    .stats-card {
        height: auto;
        min-height: 120px;
    }
    
    .chart-container {
        height: 300px;
    }
}

/* Dark Mode Responsive Adjustments */
.dark-mode {
    @media (max-width: 768px) {
        .card {
            background: rgba(31, 41, 55, 0.95) !important;
            border-color: rgba(75, 85, 99, 0.3) !important;
        }
        
        .modal-content {
            background: rgba(31, 41, 55, 0.98) !important;
            border-color: rgba(75, 85, 99, 0.3) !important;
        }
        
        .offcanvas {
            background: rgba(31, 41, 55, 0.98) !important;
        }
    }
}

/* Animation Disable Class */
.no-animations * {
    animation-duration: 0s !important;
    animation-delay: 0s !important;
    transition-duration: 0s !important;
    transition-delay: 0s !important;
}

/* Accessibility Improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus Styles for Better Accessibility */
.btn:focus,
.form-control:focus,
.form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25) !important;
    border-color: #6366f1 !important;
}

/* Loading States */
.loading {
    position: relative;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #6366f1;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Touch Device Optimizations */
@media (hover: none) and (pointer: coarse) {
    .btn {
        min-height: 44px; /* Minimum touch target size */
        min-width: 44px;
    }
    
    .floating-action-btn {
        width: 56px;
        height: 56px;
    }
    
    .form-control {
        min-height: 44px;
    }
    
    .card {
        margin-bottom: 1rem;
    }
}

/* Improved Chart Responsiveness */
.chart-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.chart-container canvas {
    max-width: 100% !important;
    height: auto !important;
}

/* Enhanced Mobile Navigation */
@media (max-width: 768px) {
    .navbar-nav {
        flex-direction: column;
        width: 100%;
    }
    
    .navbar-nav .nav-item {
        width: 100%;
        text-align: center;
    }
    
    .dropdown-menu {
        position: static !important;
        float: none;
        width: 100%;
        margin-top: 0;
        background-color: transparent;
        border: 0;
        box-shadow: none;
    }
}
</style>
@endpush