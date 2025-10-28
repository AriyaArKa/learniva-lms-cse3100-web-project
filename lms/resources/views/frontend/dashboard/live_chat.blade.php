@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')
    <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
        <div class="setting-body">
            <div class="breadcrumb-content d-flex align-items-center justify-content-between pb-4">
                <h3 class="fs-17 font-weight-semi-bold">Live Chat</h3>
                <div class="breadcrumb-list">
                    <span class="breadcrumb-item">Dashboard</span>
                    <span class="breadcrumb-item active">Live Chat</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div id="app">
                        <chat-message
                            :initial-current-user="{{ json_encode([
                                'id' => Auth::user()->id,
                                'name' => Auth::user()->name,
                                'photo' => Auth::user()->photo,
                                'role' => Auth::user()->role,
                            ]) }}"></chat-message>
                    </div>
                </div>
            </div>
        </div><!-- end setting-body -->
    </div><!-- end tab-pane -->

    <style>
        .setting-body {
            padding: 20px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
        }

        .breadcrumb-content {
            margin-bottom: 20px;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #64748b;
        }

        .breadcrumb-item {
            position: relative;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '/';
            margin-left: 8px;
            color: #cbd5e0;
        }

        .breadcrumb-item.active {
            color: #667eea;
            font-weight: 500;
        }

        /* Override any conflicting styles */
        #app {
            width: 100%;
            height: auto;
        }

        /* Ensure the chat component fits properly */
        .chat-container {
            height: 600px !important;
            border-radius: 12px !important;
            overflow: hidden !important;
        }

        /* Make sure the layout is responsive */
        @media (max-width: 768px) {
            .setting-body {
                padding: 15px;
            }

            .breadcrumb-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .chat-container {
                height: 500px !important;
            }
        }

        /* Ensure proper z-index for chat elements */
        .chat-container * {
            z-index: auto;
        }
    </style>
@endsection
