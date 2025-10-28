@extends('instructor.instructor_dashboard')
@section('instructor')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Live Chat</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

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
    </div>

    <style>
        .card {
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
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
