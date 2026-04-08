<section class="bh-section bh-section-gray" id="testimonials">
    <div class="container">
        <div class="bh-section-header">
            <h2>Reviews</h2>
        </div>

        @if($reviews->count() > 0)
        <div class="bh-testimonials-track-wrapper">
            <div class="bh-testimonials-track" id="testimonialTrack">
                @foreach($reviews as $review)
                <div class="bh-testimonial-card">
                    <div class="bh-testimonial-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="{{ $i > $review->rating ? 'color:#e2e8f0;' : '' }}"></i>
                        @endfor
                    </div>
                    <p>"{{ $review->comment }}"</p>
                    <div class="bh-testimonial-author">
                        <strong>{{ $review->name }}</strong>
                        <span>{{ $review->location ?? 'Guest' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <p class="text-center" style="color:#64748b;">No reviews yet. <a href="{{ route('reviews') }}" style="color:var(--bh-primary);font-weight:600;">Be the first!</a></p>
        @endif

        <div class="text-center" style="margin-top:28px;">
            <a href="{{ route('reviews') }}" class="btn bh-btn-primary"><i class="fas fa-pen" style="margin-right:6px;"></i> Write a Review</a>
        </div>
    </div>
</section>
