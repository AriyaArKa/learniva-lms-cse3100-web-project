@extends('instructor.instructor_dashboard')
@section('instructor')

    @php
        $id = Auth::user()->id;
        $instructorId = App\Models\User::find($id);
        $status = $instructorId->status;
    @endphp


    <div class="page-content">

        @if ($status === '1')
            <h4>Instructor account is <span class="text-success">active</span></h4>
        @else
            <h4>Instructor account is <span class="text-danger">inactive</span></h4>
            <p class="text-danger">Please wait admin will cheack and approve your account</p>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Orders</p>
                                <h4 class="my-1 text-info">{{ $totalOrders }}</h4>
                                <p class="mb-0 font-13">Confirmed orders</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                    class='bx bxs-cart'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-4 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Revenue</p>
                                <h4 class="my-1 text-danger">${{ number_format($totalRevenue, 2) }}</h4>
                                <p class="mb-0 font-13">From course sales</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                <i class='bx bxs-wallet'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-4 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Courses</p>
                                <h4 class="my-1 text-success">{{ $totalCourses }}</h4>
                                <p class="mb-0 font-13">Active courses</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class='bx bxs-bar-chart-alt-2'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Students</p>
                                <h4 class="my-1 text-warning">{{ $totalStudents }}</h4>
                                <p class="mb-0 font-13">Enrolled students</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->

        <div class="row">
            <div class="col-12 col-lg-12 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Sales Overview</h6>
                            </div>
                            <div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                    data-bs-toggle="dropdown"><i
                                        class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1"
                                    style="color: #14abef"></i>Revenue ($)</span>
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1"
                                    style="color: #ffc107"></i>Orders</span>
                        </div>
                        <div class="chart-container-1">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">{{ $totalOrders }}</h5>
                                <small class="mb-0">Total Orders <span> <i class="bx bx-cart align-middle"></i>
                                        Completed</span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">${{ number_format($totalRevenue, 2) }}</h5>
                                <small class="mb-0">Total Earnings <span>
                                        @if ($revenueChange >= 0)
                                            <i class="bx bx-up-arrow-alt align-middle"></i> {{ $revenueChange }}%
                                        @else
                                            <i class="bx bx-down-arrow-alt align-middle"></i> {{ abs($revenueChange) }}%
                                        @endif
                                    </span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">{{ $totalStudents }}</h5>
                                <small class="mb-0">Enrolled Students <span> <i class="bx bx-user align-middle"></i>
                                        Active</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--end row-->

        <div class="card radius-10">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Recent Orders</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Course Title</th>
                                <th>Student</th>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Payment Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>{{ Str::limit($order->course_title, 30) }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>#{{ $order->payment->invoice_no ?? $order->id }}</td>
                                    <td>
                                        @if ($order->payment && $order->payment->status == 'complete')
                                            <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Paid</span>
                                        @elseif($order->payment && $order->payment->status == 'pending')
                                            <span
                                                class="badge bg-gradient-blooker text-white shadow-sm w-100">Pending</span>
                                        @else
                                            <span class="badge bg-gradient-bloody text-white shadow-sm w-100">Failed</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->price, 2) }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if ($order->payment)
                                            @if ($order->payment->status == 'complete')
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-quepal" role="progressbar"
                                                        style="width: 100%"></div>
                                                </div>
                                            @elseif($order->payment->status == 'pending')
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-blooker" role="progressbar"
                                                        style="width: 60%"></div>
                                                </div>
                                            @else
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-bloody" role="progressbar"
                                                        style="width: 40%"></div>
                                                </div>
                                            @endif
                                            <small class="text-muted">{{ $order->payment->payment_type ?? 'N/A' }}</small>
                                        @else
                                            <small class="text-muted">N/A</small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No orders yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>
@endsection

@push('scripts')
    <script>
        // Override the default chart1 with instructor's dynamic data
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById("chart1");
            if (ctx) {
                ctx = ctx.getContext('2d');

                // Clear any existing chart
                if (window.chart1Instance) {
                    window.chart1Instance.destroy();
                }

                var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke1.addColorStop(0, '#6078ea');
                gradientStroke1.addColorStop(1, '#17c5ea');

                var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke2.addColorStop(0, '#ff8359');
                gradientStroke2.addColorStop(1, '#ffdf40');

                var monthlyRevenue = @json($monthlyRevenueData);
                var monthlyOrders = @json($monthlyOrdersData);

                window.chart1Instance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                            'Nov', 'Dec'
                        ],
                        datasets: [{
                            label: 'Revenue ($)',
                            data: monthlyRevenue,
                            borderColor: gradientStroke1,
                            backgroundColor: gradientStroke1,
                            hoverBackgroundColor: gradientStroke1,
                            pointRadius: 0,
                            fill: false,
                            borderRadius: 20,
                            borderWidth: 0
                        }, {
                            label: 'Orders',
                            data: monthlyOrders,
                            borderColor: gradientStroke2,
                            backgroundColor: gradientStroke2,
                            hoverBackgroundColor: gradientStroke2,
                            pointRadius: 0,
                            fill: false,
                            borderRadius: 20,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        barPercentage: 0.5,
                        categoryPercentage: 0.8,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            if (context.datasetIndex === 0) {
                                                label += '$' + context.parsed.y.toFixed(2);
                                            } else {
                                                label += context.parsed.y;
                                            }
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
