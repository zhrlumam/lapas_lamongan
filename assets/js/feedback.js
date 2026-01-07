function showFeedbackPopup(layanan) {
    Swal.fire({
        title: 'Bantu Kami Menjadi Lebih Baik!',
        html: `
            <div class="text-sm text-slate-500 mb-4">Bagaimana penilaian Anda terhadap layanan <strong>${layanan}</strong> kami?</div>
            <div class="flex justify-center gap-2 mb-4" id="rating-stars">
                ${[1, 2, 3, 4, 5].map(i => `
                    <button type="button" onclick="setRating(${i})" class="star-btn text-3xl text-slate-200 transition-all hover:scale-110" data-value="${i}">
                        ★
                    </button>
                `).join('')}
            </div>
            <input type="hidden" id="feedback-rating" value="0">
            <textarea id="feedback-ulasan" class="w-full border-2 border-slate-100 rounded-xl p-4 text-sm outline-none focus:border-imipas-gold bg-slate-50" placeholder="Berikan ulasan Anda (opsional)..."></textarea>
        `,
        showCancelButton: true,
        confirmButtonText: 'Kirim Penilaian',
        cancelButtonText: 'Nanti Saja',
        confirmButtonColor: '#07213D',
        preConfirm: () => {
            const rating = document.getElementById('feedback-rating').value;
            const ulasan = document.getElementById('feedback-ulasan').value;
            if (rating === "0") {
                Swal.showValidationMessage('Silakan pilih rating bintang terlebih dahulu');
                return false;
            }
            return { rating, ulasan };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('layanan', layanan);
            formData.append('rating', result.value.rating);
            formData.append('ulasan', result.value.ulasan);

            fetch('api/submit_feedback.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Terima Kasih!', 'Penilaian Anda telah kami terima.', 'success');
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                });
        }
    });
}

function setRating(val) {
    document.getElementById('feedback-rating').value = val;
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach((star, index) => {
        if (index < val) {
            star.classList.remove('text-slate-200');
            star.classList.add('text-amber-400');
        } else {
            star.classList.remove('text-amber-400');
            star.classList.add('text-slate-200');
        }
    });
}
