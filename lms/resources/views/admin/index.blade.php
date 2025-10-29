@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Orders</p>
                                <h4 class="my-1 text-info">{{ number_format($totalOrders) }}</h4>
                                <p class="mb-0 font-13">
                                    @if ($ordersChange >= 0)
                                        <i class='bx bx-up-arrow-alt text-success'></i> +{{ $ordersChange }}%
                                    @else
                                        <i class='bx bx-down-arrow-alt text-danger'></i> {{ $ordersChange }}%
                                    @endif
                                    from last week
                                </p>
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
                                <p class="mb-0 font-13">
                                    @if ($revenueChange >= 0)
                                        <i class='bx bx-up-arrow-alt text-success'></i> +{{ $revenueChange }}%
                                    @else
                                        <i class='bx bx-down-arrow-alt text-danger'></i> {{ $revenueChange }}%
                                    @endif
                                    from last week
                                </p>
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
                                <h4 class="my-1 text-success">{{ number_format($totalCourses) }}</h4>
                                <p class="mb-0 font-13">Active courses</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class='bx bxs-book-content'></i>
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
                                <p class="mb-0 text-secondary">Total Customers</p>
                                <h4 class="my-1 text-warning">{{ number_format($totalCustomers) }}</h4>
                                <p class="mb-0 font-13">
                                    @if ($customersChange >= 0)
                                        <i class='bx bx-up-arrow-alt text-success'></i> +{{ $customersChange }}%
                                    @else
                                        <i class='bx bx-down-arrow-alt text-danger'></i> {{ $customersChange }}%
                                    @endif
                                    from last week
                                </p>
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
                                    style="color: #14abef"></i>Orders</span>
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1"
                                    style="color: #ffc107"></i>Revenue ($)</span>
                        </div>
                        <div class="chart-container-1">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">{{ number_format($totalOrders) }}</h5>
                                <small class="mb-0">Total Orders <span> <i class="bx bx-up-arrow-alt align-middle"></i>
                                        {{ abs($ordersChange) }}%</span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">${{ number_format($totalRevenue, 2) }}</h5>
                                <small class="mb-0">Total Revenue <span> <i class="bx bx-up-arrow-alt align-middle"></i>
                                        {{ abs($revenueChange) }}%</span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">{{ number_format($totalCourses) }}</h5>
                                <small class="mb-0">Active Courses</small>
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
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i
                                class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.all.course') }}">View All Courses</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('all.instructor') }}">View All Instructors</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Course Name</th>
                                <th>Student</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ !empty($order->course->course_image) ? url($order->course->course_image) : url('upload/no_image.jpg') }}"
                                                class="product-img-2" alt="course img">
                                            <span
                                                class="ms-2">{{ Str::limit($order->course->course_name ?? 'N/A', 30) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
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
                                    <td>${{ number_format($order->price ?? 0, 2) }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if ($order->payment)
                                            @if (strtolower($order->payment->payment_type) == 'sslcommerz' || strtolower($order->payment->payment_type) == 'ssl')
                                                <span class="badge bg-info text-white">
                                                    <i class='bx bx-credit-card me-1'></i>SSLCommerz
                                                </span>
                                            @elseif(strtolower($order->payment->payment_type) == 'direct' || strtolower($order->payment->payment_type) == 'cash')
                                                <span class="badge bg-success text-white">
                                                    <i class='bx bx-money me-1'></i>Direct Payment
                                                </span>
                                            @elseif(strtolower($order->payment->payment_type) == 'stripe')
                                                <span class="badge bg-primary text-white">
                                                    <i class='bx bxl-stripe me-1'></i>Stripe
                                                </span>
                                            @elseif(strtolower($order->payment->payment_type) == 'paypal')
                                                <span class="badge bg-warning text-dark">
                                                    <i class='bx bxl-paypal me-1'></i>PayPal
                                                </span>
                                            @else
                                                <span class="badge bg-secondary text-white">
                                                    <i
                                                        class='bx bx-wallet me-1'></i>{{ ucfirst($order->payment->payment_type) }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary text-white">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="mb-0 text-muted">No orders found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Sales Overview Chart
                var ctx = document.getElementById("chart1");
                if (ctx) {
                    ctx = ctx.getContext('2d');

                    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
                    gradientStroke1.addColorStop(0, '#6078ea');
                    gradientStroke1.addColorStop(1, '#17c5ea');

                    var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
                    gradientStroke2.addColorStop(0, '#ff8359');
                    gradientStroke2.addColorStop(1, '#ffdf40');

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                                'Nov', 'Dec'
                            ],
                            datasets: [{
                                label: 'Orders',
                                data: @json($monthlySales),
                                borderColor: gradientStroke1,
                                backgroundColor: gradientStroke1,
                                hoverBackgroundColor: gradientStroke1,
                                pointRadius: 0,
                                fill: false,
                                borderRadius: 20,
                                borderWidth: 0
                            }, {
                                label: 'Revenue ($)',
                                data: @json($monthlyRevenue),
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
@endsection
