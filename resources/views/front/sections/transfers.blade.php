<section id="transfers" class="bh-section">
    <div class="container">
        <div class="bh-section-header">
            <h2>Transfers</h2>
        </div>

        <div class="bh-transfer-grid">
            @foreach($transferRoutes as $route)
                <div class="bh-transfer-card"
                     data-route="{{ $route->title }}"
                     data-price-1-4="{{ $route->price_1_4 ?? 0 }}"
                     data-price-5-6="{{ $route->price_5_6 ?? 0 }}"
                     data-price-7-8="{{ $route->price_7_8 ?? 0 }}"
                     data-price-9-14="{{ $route->price_9_14 ?? 0 }}">
                    <div class="bh-transfer-card-icon">
                        <i class="fas {{ $route->icon ?? 'fa-shuttle-van' }}"></i>
                    </div>
                    <div class="bh-transfer-card-body" style="text-align:center;">
                        <h4>{{ $route->title }}</h4>
                        @if(($route->price_1_4 ?? 0) > 0)
                            <div class="tf-card-price">From <strong>£{{ number_format($route->price_1_4, 0) }}</strong></div>
                        @endif
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
                {{-- Honeypot — hidden from users, bots fill it --}}
                <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;opacity:0;pointer-events:none;height:0;width:0;" aria-hidden="true">
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
                            <input type="tel" name="phone" class="form-control" placeholder="+44 7911 123456" required
                                   pattern="[\+]?[0-9\s\-\(\)]{7,20}" title="Please enter a valid phone number">
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
                    <div class="col-md-12 mb-3">
                        <div class="bh-input-group">
                            <i class="fas fa-users"></i>
                            <input type="text" name="adult_names" class="form-control" placeholder="All Passengers Name — separate with comma (e.g. John Smith, Jane Smith, Tom Smith)">
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
                <div class="tf-price-bar" id="transferPriceBar" style="display:none;background:linear-gradient(135deg,#1e3a5f,#0066cc)!important;color:#fff!important;">
                    <span style="color:#fff!important;">Total Price <small id="transferPaxLabel" style="font-weight:500;color:#fff!important;"></small></span>
                    <strong id="transferPriceDisplay" style="color:#fff!important;"></strong>
                </div>

                <button type="submit" class="btn bh-btn-lg w-100 tf-submit-btn">
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
.tf-card-price {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 18px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--bh-primary), var(--bh-secondary));
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.3px;
    box-shadow: 0 6px 18px rgba(0,102,204,0.28);
}
.tf-card-price strong {
    font-size: 20px;
    font-weight: 800;
    margin-left: 4px;
}
.tf-section-title i { color: var(--bh-primary); font-size: 15px; }
[data-theme="dark"] .tf-section-title { color: #ffffff; }
[data-theme="dark"] .tf-section-title i { color: #ffffff; }
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
.tf-price-bar small { color: #fff; }
.tf-price-bar strong { font-size: 22px; font-weight: 800; color: #fff; }
.tf-submit-btn {
    background: var(--bh-secondary);
    color: #fff;
    border: none;
    font-weight: 700;
    border-radius: 12px;
    transition: all 0.25s;
}
.tf-submit-btn:hover {
    background: #0066cc;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,102,204,0.35);
}
html[data-theme="dark"] input[type="date"],
html[data-theme="dark"] input[type="time"],
html[data-theme="dark"] input[type="datetime-local"] {
    color-scheme: dark !important;
}
html[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator,
html[data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator,
html[data-theme="dark"] input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    cursor: pointer !important;
    opacity: 1 !important;
}
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
