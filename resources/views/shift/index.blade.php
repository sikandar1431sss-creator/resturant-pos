@extends('layouts.master')

@section('title')
    Shift Closing & Daily Cash Drawer (Z-Report)
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Shift Closing</li>
@endsection

@section('content')
<div class="row">
    <!-- Active Shift Card -->
    <div class="col-lg-12">
        <div class="box box-solid" style="border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; border-radius: 8px 8px 0 0; padding: 15px 20px;">
                <h3 class="box-title" style="font-weight: 700; font-size: 18px;">
                    <i class="fa fa-clock-o text-warning"></i> Active Cashier Shift: {{ Auth::user()->name }}
                </h3>
                <div class="box-tools pull-right">
                    @if($currentShift)
                        <span class="badge" style="background: #10b981; color: #fff; font-size: 13px; padding: 6px 12px; border-radius: 20px;">
                            🟢 SHIFT IN PROGRESS (Opened: {{ date('d M, h:i A', strtotime($currentShift->opened_at)) }})
                        </span>
                    @else
                        <span class="badge" style="background: #ef4444; color: #fff; font-size: 13px; padding: 6px 12px; border-radius: 20px;">
                            🔴 NO ACTIVE SHIFT
                        </span>
                    @endif
                </div>
            </div>

            <div class="box-body" style="padding: 25px;">
                @if(!$currentShift)
                    <div class="text-center" style="padding: 30px 0;">
                        <i class="fa fa-calculator text-muted" style="font-size: 55px; margin-bottom: 15px;"></i>
                        <h4>Start a New Cashier Shift</h4>
                        <p class="text-muted">Enter your initial opening cash (float) to begin taking orders and tracking drawer cash.</p>
                        <button onclick="openStartShiftModal()" class="btn btn-success btn-lg" style="border-radius: 6px; font-weight: 600; padding: 10px 25px;">
                            <i class="fa fa-play-circle"></i> Start Shift / Open Drawer
                        </button>
                    </div>
                @else
                    <!-- Live Shift Stats Grid -->
                    <div class="row" id="shift-live-stats">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-navy" style="border-radius: 8px;">
                                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Opening Float</span>
                                    <span class="info-box-number" id="stat-opening-cash">{{ format_currency($currentShift->opening_cash) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-green" style="border-radius: 8px;">
                                <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cash Sales</span>
                                    <span class="info-box-number" id="stat-cash-sales">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-aqua" style="border-radius: 8px;">
                                <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Card / Online</span>
                                    <span class="info-box-number" id="stat-card-sales">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-red" style="border-radius: 8px;">
                                <span class="info-box-icon"><i class="fa fa-minus-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Expenses</span>
                                    <span class="info-box-number" id="stat-expenses">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-top: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                        <div>
                            <span style="font-size: 14px; color: #64748b;">Expected Drawer Cash (Opening + Cash Sales - Expenses):</span>
                            <h2 style="margin: 5px 0; font-weight: 800; color: #0f172a;" id="stat-expected-cash">Loading...</h2>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button onclick="fetchSummary()" class="btn btn-default" style="border-radius: 6px;"><i class="fa fa-refresh"></i> Refresh Stats</button>
                            <button onclick="openCloseShiftModal()" class="btn btn-danger" style="border-radius: 6px; font-weight: 700; padding: 8px 20px;">
                                <i class="fa fa-lock"></i> Close Shift & Settle Drawer (Z-Report)
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Shift History Table -->
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: 700;"><i class="fa fa-history"></i> Recent Shift Closings & Z-Reports History</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <th>#</th>
                        <th>Cashier / User</th>
                        <th>Shift Period</th>
                        <th>Opening Float</th>
                        <th>Cash Sales</th>
                        <th>Card Sales</th>
                        <th>Expenses</th>
                        <th>Expected Drawer</th>
                        <th>Actual Counted</th>
                        <th>Difference</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @forelse($history as $i => $h)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $h->user->name ?? 'Staff' }}</strong></td>
                            <td>
                                {{ date('d M, H:i', strtotime($h->opened_at)) }} 
                                @if($h->closed_at)
                                    <span class="text-muted">➔</span> {{ date('d M, H:i', strtotime($h->closed_at)) }}
                                @else
                                    <span class="badge bg-green">Active</span>
                                @endif
                            </td>
                            <td>{{ format_currency($h->opening_cash) }}</td>
                            <td>{{ format_currency($h->total_cash_sales) }}</td>
                            <td>{{ format_currency($h->total_card_sales + $h->total_online_sales) }}</td>
                            <td class="text-danger">{{ format_currency($h->total_expense) }}</td>
                            <td><strong>{{ format_currency($h->expected_cash) }}</strong></td>
                            <td style="font-weight: 700; color: #0284c7;">{{ format_currency($h->actual_cash) }}</td>
                            <td>
                                @if($h->difference < 0)
                                    <span class="badge" style="background: #dc2626; color: #fff;">Short: {{ format_currency(abs($h->difference)) }}</span>
                                @elseif($h->difference > 0)
                                    <span class="badge" style="background: #16a34a; color: #fff;">Over: {{ format_currency($h->difference) }}</span>
                                @else
                                    <span class="badge" style="background: #64748b; color: #fff;">Exact (0)</span>
                                @endif
                            </td>
                            <td>
                                @if($h->status == 'open')
                                    <span class="badge bg-yellow">Open</span>
                                @else
                                    <span class="badge bg-navy">Closed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No past shift records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Start Shift -->
<div class="modal fade" id="modal-start-shift" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <form action="{{ route('shift.start') }}" method="post">
            @csrf
            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: #10b981; color: #fff;">
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title font-weight-bold"><i class="fa fa-play"></i> Open Cashier Shift</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Opening Cash in Drawer (Float) <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="opening_cash" class="form-control" value="0" required min="0" style="border-radius: 6px; font-size: 16px; font-weight: bold;">
                        <small class="text-muted">Enter change/cash available in the drawer at start.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success font-weight-bold">Start Shift</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Close Shift / Z-Report -->
<div class="modal fade" id="modal-close-shift" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('shift.close') }}" method="post">
            @csrf
            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: #1e293b; color: #fff;">
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title font-weight-bold"><i class="fa fa-calculator"></i> Close Shift & Settle Drawer (Z-Report)</h4>
                </div>
                <div class="modal-body">
                    <div style="background: #f1f5f9; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span>System Calculated Expected Cash:</span>
                            <strong id="modal-expected-cash-label" style="font-size: 16px; color: #0284c7;">-</strong>
                        </div>
                        <small class="text-muted">Formula: (Opening Float + Cash Sales) - Expenses</small>
                    </div>

                    <div class="form-group">
                        <label>Physical Counted Cash in Drawer <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="actual_cash" id="actual_cash_input" class="form-control" required min="0" placeholder="Count and enter total cash" style="border-radius: 6px; font-size: 18px; font-weight: bold;">
                    </div>

                    <div class="form-group">
                        <label>Notes / Remarks (Optional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Any shortage reasons or drawer notes..." style="border-radius: 6px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger font-weight-bold"><i class="fa fa-lock"></i> Submit & Close Shift</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        @if($currentShift)
            fetchSummary();
        @endif

        $('#modal-start-shift form').on('submit', function(e) {
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serialize())
                .done((res) => {
                    $('#modal-start-shift').modal('hide');
                    showSuccessToast(res.message);
                    setTimeout(() => location.reload(), 600);
                })
                .fail((err) => {
                    showErrorToast('Failed to start shift');
                });
        });

        $('#modal-close-shift form').on('submit', function(e) {
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serialize())
                .done((res) => {
                    $('#modal-close-shift').modal('hide');
                    showSuccessToast(res.message);
                    setTimeout(() => location.reload(), 800);
                })
                .fail((err) => {
                    showErrorToast('Failed to close shift');
                });
        });
    });

    let currentExpectedCash = 0;

    function fetchSummary() {
        $.get('{{ route('shift.summary') }}', function(data) {
            $('#stat-cash-sales').text(data.formatted_cash_sales);
            $('#stat-card-sales').text(data.formatted_card_sales);
            $('#stat-expenses').text(data.formatted_expense);
            $('#stat-expected-cash').text(data.formatted_expected_cash);
            currentExpectedCash = data.expected_cash;
            $('#modal-expected-cash-label').text(data.formatted_expected_cash);
        });
    }

    function openStartShiftModal() {
        $('#modal-start-shift').modal('show');
    }

    function openCloseShiftModal() {
        fetchSummary();
        $('#modal-close-shift').modal('show');
    }
</script>
@endpush
