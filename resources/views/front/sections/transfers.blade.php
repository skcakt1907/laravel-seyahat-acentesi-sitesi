<section id="transfers" class="bh-section">
    <div class="container">
        <div class="bh-section-header">
            <span class="bh-section-tag">Airport Transfers</span>
            <h2>Popular Transfer Routes</h2>
            <p>Safe, comfortable and affordable private transfers from Dalaman Airport</p>
        </div>

        <div class="bh-transfer-grid">
            @foreach($transferRoutes as $route)
                <div class="bh-transfer-card" data-route="{{ $route->title }}" data-price="{{ $route->price }}">
                    <div class="bh-transfer-card-icon">
                        <i class="fas {{ $route->icon ?? 'fa-shuttle-van' }}"></i>
                    </div>
                    <div class="bh-transfer-card-body">
                        <h4>{{ $route->title }}</h4>
                        @if($route->price > 0)
                            <span style="font-size:13px;font-weight:700;color:var(--bh-secondary);">From £{{ number_format($route->price, 0) }}</span>
                        @endif
                        <span class="bh-transfer-card-cta">Book Now <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="transferFormPanel" class="bh-transfer-form-panel">
            <div class="bh-form-header">
                <h3><i class="fas fa-map-marker-alt"></i> <span id="selectedRouteTitle">Select a route above</span></h3>
                <button type="button" class="bh-form-close" id="closeTransferForm">&times;</button>
            </div>
            <form action="{{ route('transfer.submit') }}" method="POST" class="bh-form">
                @csrf
                <input type="hidden" name="package" id="transferRouteInput" value="">

                {{-- Personal Details --}}
                <div class="tf-section-title"><i class="fas fa-user"></i> Personal Details</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                    </div>
                </div>

                {{-- Booking Details --}}
                <div class="tf-section-title"><i class="fas fa-hotel"></i> Booking Details</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-hotel"></i>
                            <input type="text" name="hotel_name" class="form-control" placeholder="Hotel Name" required>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-users"></i>
                            <input type="number" name="adult_count" class="form-control" placeholder="Adults" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-child"></i>
                            <input type="number" name="child_count" class="form-control" placeholder="Children" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-users"></i>
                            <input type="text" name="adult_names" class="form-control" placeholder="Adult Names (e.g. John Smith, Jane Smith)">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-child"></i>
                            <input type="text" name="child_names" class="form-control" placeholder="Child Names (if any)">
                        </div>
                    </div>
                </div>

                {{-- Arrival Details --}}
                <div class="tf-section-title"><i class="fas fa-plane-arrival"></i> Arrival Details</div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-calendar"></i>
                            <input type="date" name="arrival_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-clock"></i>
                            <input type="time" name="arrival_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-plane"></i>
                            <input type="text" name="arrival_flight" class="form-control" placeholder="Flight No (e.g. LS1849)" required>
                        </div>
                    </div>
                </div>

                {{-- Departure Details --}}
                <div class="tf-section-title"><i class="fas fa-plane-departure"></i> Departure Details</div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-calendar"></i>
                            <input type="date" name="departure_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-clock"></i>
                            <input type="time" name="departure_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-plane"></i>
                            <input type="text" name="departure_flight" class="form-control" placeholder="Flight No (e.g. LS1850)" required>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mb-3">
                    <textarea name="notes" class="form-control" placeholder="Notes or special requests..." rows="3" style="height:auto;border:2px solid #e2e8f0;border-radius:10px;padding:12px 14px;font-size:14px;font-family:'Poppins',sans-serif;color:var(--bh-dark);"></textarea>
                </div>

                {{-- Price Display --}}
                <div class="tf-price-bar" id="transferPriceBar" style="display:none;">
                    <span>Transfer Price:</span>
                    <strong id="transferPriceDisplay"></strong>
                </div>

                <button type="submit" class="btn bh-btn-primary bh-btn-lg w-100">
                    <i class="fas fa-check-circle"></i> Complete Reservation
                </button>
            </form>
        </div>
    </div>
</section>

<style>
.tf-section-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--bh-dark);
    margin: 18px 0 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
}
.tf-section-title:first-of-type { margin-top: 0; }
.tf-section-title i { color: var(--bh-primary); font-size: 15px; }
.tf-price-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, var(--bh-primary), var(--bh-secondary));
    color: #fff;
    padding: 14px 20px;
    border-radius: 10px;
    margin-bottom: 16px;
    font-size: 15px;
}
.tf-price-bar strong { font-size: 22px; font-weight: 800; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var arrDate = document.querySelector('input[name="arrival_date"]');
    var depDate = document.querySelector('input[name="departure_date"]');
    if (arrDate && depDate) {
        arrDate.addEventListener('change', function() {
            depDate.min = this.value;
            if (depDate.value && depDate.value < this.value) depDate.value = this.value;
        });
    }
});
</script>
