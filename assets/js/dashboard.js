// Dashboard JavaScript for Swiper and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for popular products
    if (document.querySelector('.nxt-products-swiper')) {
        const productsSwiper = new Swiper('.nxt-products-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.nxt-carousel-next',
                prevEl: '.nxt-carousel-prev',
            },
            breakpoints: {
                480: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24
                },
            }
        });
    }

    // Order filtering functionality
    const filterSelect = document.getElementById('nxt-order-filter');
    if (filterSelect) {
       
        const noMatchesMessage = document.createElement('div');
        noMatchesMessage.className = 'nxt-no-matches';
        noMatchesMessage.style.display = 'none';
        noMatchesMessage.innerHTML = `
            <div class="nxt-no-matches-content">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>No orders found</h3>
                <p>No orders match the selected filter. Try selecting a different status or clear the filter to see all orders.</p>
            </div>
        `;
        

        const ordersList = document.querySelector('.nxt-orders-list');
        if (ordersList) {
            ordersList.parentNode.insertBefore(noMatchesMessage, ordersList.nextSibling);
        }
        
        filterSelect.addEventListener('change', function() {
            const selectedStatus = this.value;
            const orderCards = document.querySelectorAll('.nxt-order-card');
            let visibleCount = 0;
            
            orderCards.forEach(card => {
                if (selectedStatus === '') {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    const orderStatus = card.getAttribute('data-status');
                    if (orderStatus === selectedStatus) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Show/hide no matches message
            if (visibleCount === 0 && selectedStatus !== '') {
                noMatchesMessage.style.display = 'block';
            } else {
                noMatchesMessage.style.display = 'none';
            }
        });
    }

});
