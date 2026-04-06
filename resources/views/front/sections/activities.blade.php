<section id="activities" class="bh-section bh-section-gray">
    <div class="container">
        <div class="bh-section-header">
            <span class="bh-section-tag">Extra Packages</span>
            <h2>Popular Activities</h2>
            <p>Handpicked experiences to make your holiday unforgettable</p>
        </div>

        <div class="bh-dest-grid">
            @foreach($activities as $activity)
                <div class="bh-dest-card-wrap">
                    <div class="bh-dest-card" style="cursor:default;">
                        @if($activity->image)
                            <img src="{{ asset('tema/uploads/activities/' . $activity->image) }}" alt="{{ $activity->title }}">
                        @else
                            <div class="bh-dest-placeholder"><i class="fas fa-mountain-sun"></i></div>
                        @endif
                        <div class="bh-dest-overlay"></div>
                        <div class="bh-dest-text">
                            <span class="bh-dest-title"><i class="fas fa-map-marker-alt"></i> {{ $activity->title }}</span>
                        </div>
                        <div class="bh-dest-badge">
                            @if($activity->price > 0)
                                From £{{ number_format($activity->price, 0) }}
                            @elseif($activity->badge)
                                {{ $activity->badge }}
                            @else
                                View Details
                            @endif
                        </div>
                        {{-- Hover Buttons --}}
                        <div class="bh-dest-actions">
                            <a href="{{ route('activity.detail', $activity->slug) }}" class="bh-dest-btn detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <button type="button" class="bh-dest-btn buy" onclick="openBuyModal('{{ $activity->slug }}', '{{ addslashes($activity->title) }}', '{{ $activity->price }}')">
                                <i class="fas fa-bolt"></i> Buy
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Quick Buy Modal --}}
<div class="qb-overlay" id="qbOverlay" onclick="closeBuyModal()"></div>
<div class="qb-modal" id="qbModal">
    <button class="qb-close" onclick="closeBuyModal()">&times;</button>
    <div class="qb-header">
        <div class="qb-header-icon"><i class="fas fa-bolt"></i></div>
        <h3>Quick Buy</h3>
        <p id="qbActivityTitle" class="qb-subtitle"></p>
        <div class="qb-price" id="qbPrice"></div>
    </div>
    <form id="qbForm" method="POST" class="qb-form">
        @csrf
        <input type="hidden" name="activity_name" id="qbActivityName">

        <div class="qb-section-title"><i class="fas fa-user"></i> Your Information</div>
        <div class="qb-row">
            <div class="qb-field">
                <input type="text" name="first_name" placeholder="First Name" required>
            </div>
            <div class="qb-field">
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>
        </div>
        <div class="qb-row">
            <div class="qb-field">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="qb-field">
                <input type="text" name="phone" placeholder="Phone Number" required>
            </div>
        </div>

        <button type="submit" class="qb-submit">
            <i class="fas fa-bolt"></i> Complete Purchase
        </button>
    </form>
</div>

<style>
/* Hover action buttons on activity cards */
.bh-dest-card-wrap { position: relative; }
.bh-dest-card-wrap .bh-dest-card {
    position: relative;
    display: block;
    height: 400px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
}
.bh-dest-card-wrap .bh-dest-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 16px 40px rgba(0,0,0,0.18);
}
.bh-dest-actions {
    position: absolute;
    bottom: 70px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 12px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 5;
}
.bh-dest-card-wrap:hover .bh-dest-actions {
    opacity: 1;
    transform: translateY(0);
}
.bh-dest-btn {
    padding: 12px 28px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    border: none;
    transition: all 0.25s;
    backdrop-filter: blur(8px);
}
.bh-dest-btn.detail {
    background: rgba(255,255,255,0.95);
    color: var(--bh-dark);
}
.bh-dest-btn.detail:hover {
    background: #fff;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    text-decoration: none;
    color: var(--bh-dark);
}
.bh-dest-btn.buy {
    background: var(--bh-secondary);
    color: #fff;
}
.bh-dest-btn.buy:hover {
    background: #e05500;
    box-shadow: 0 6px 20px rgba(255,107,0,0.4);
}

/* Quick Buy Modal */
.qb-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 9998;
}
.qb-overlay.open { display: block; }

.qb-modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: #fff;
    border-radius: 20px;
    width: 480px;
    max-width: 94vw;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 9999;
    box-shadow: 0 30px 80px rgba(0,0,0,0.25);
    opacity: 0;
    transition: all 0.3s ease;
}
.qb-modal.open {
    display: block;
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.qb-close {
    position: absolute;
    top: 16px;
    right: 18px;
    background: none;
    border: none;
    font-size: 24px;
    color: rgba(255,255,255,0.8);
    cursor: pointer;
    z-index: 2;
    transition: color 0.2s;
}
.qb-close:hover { color: #fff; }

.qb-header {
    background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary));
    padding: 28px 28px 22px;
    text-align: center;
    border-radius: 20px 20px 0 0;
}
.qb-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--bh-secondary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
    margin-bottom: 12px;
}
.qb-header h3 {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}
.qb-subtitle {
    font-size: 14px;
    color: rgba(255,255,255,0.7);
    margin: 0 0 10px;
}
.qb-price {
    font-size: 28px;
    font-weight: 800;
    color: #fff;
}

.qb-form {
    padding: 24px 28px 28px;
}
.qb-section-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--bh-dark);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.qb-section-title i { color: var(--bh-primary); }
.qb-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}
.qb-field {
    flex: 1;
}
.qb-field input {
    width: 100%;
    height: 48px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0 14px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: var(--bh-dark);
    transition: all 0.2s;
    background: #fff;
}
.qb-field input:focus {
    border-color: var(--bh-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
}
.qb-field input::placeholder { color: #94a3b8; }

.qb-submit {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    background: var(--bh-secondary);
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 8px;
    transition: all 0.3s;
}
.qb-submit:hover {
    background: #e05500;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255,107,0,0.3);
}

@media (max-width: 576px) {
    .qb-row { flex-direction: column; gap: 10px; }
    .bh-dest-actions { gap: 8px; }
    .bh-dest-btn { padding: 10px 20px; font-size: 13px; }
}
</style>

<script>
function openBuyModal(slug, title, price) {
    document.getElementById('qbForm').action = '/activity/' + slug + '/buy';
    document.getElementById('qbActivityName').value = title;
    document.getElementById('qbActivityTitle').textContent = title;
    document.getElementById('qbPrice').textContent = price > 0 ? '£' + parseInt(price) : '';
    document.getElementById('qbOverlay').classList.add('open');
    document.getElementById('qbModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeBuyModal() {
    document.getElementById('qbOverlay').classList.remove('open');
    document.getElementById('qbModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeBuyModal();
});
</script>
