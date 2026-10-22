import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('carousel', () => ({
        atStart: true,
        atEnd: false,
        init() {
            this.update();
            this.$refs.track.addEventListener('scroll', () => this.update(), { passive: true });
            window.addEventListener('resize', () => this.update());
        },
        update() {
            const track = this.$refs.track;
            if (!track) return;
            const max = track.scrollWidth - track.clientWidth;
            this.atStart = track.scrollLeft <= 4;
            this.atEnd = max <= 4 || track.scrollLeft >= max - 4;
        },
        move(direction) {
            const track = this.$refs.track;
            const card = track.firstElementChild;
            const gap = parseFloat(getComputedStyle(track).columnGap) || 16;
            const step = card ? card.getBoundingClientRect().width + gap : track.clientWidth * 0.8;
            track.scrollBy({ left: direction * step, behavior: 'smooth' });
        }
    }));

    Alpine.store('cart', {
        items: JSON.parse(localStorage.getItem('ferplec_cart') || '[]'),
        add(product) {
            const existing = this.items.find(i => i.code === product.code && i.variant === product.variant);
            if (existing) {
                existing.qty = Math.min((existing.qty || 0) + (product.qty || 1), product.stock || 999);
            } else {
                this.items.push({ ...product, qty: product.qty || 1 });
            }
            this.items = [...this.items];
            localStorage.setItem('ferplec_cart', JSON.stringify(this.items));
        },
        remove(index) {
            this.items.splice(index, 1);
            this.items = [...this.items];
            localStorage.setItem('ferplec_cart', JSON.stringify(this.items));
        },
        updateQty(index, qty) {
            if (qty <= 0) return this.remove(index);
            this.items[index].qty = qty;
            this.items = [...this.items];
            localStorage.setItem('ferplec_cart', JSON.stringify(this.items));
        },
        clear() {
            this.items = [];
            localStorage.setItem('ferplec_cart', JSON.stringify(this.items));
        },
        count() {
            return this.items.reduce((sum, i) => sum + (i.qty || 0), 0);
        },
        subtotal() {
            return this.items.reduce((sum, i) => sum + ((i.price || 0) * (i.qty || 0)), 0);
        }
    });
});

Alpine.start();
