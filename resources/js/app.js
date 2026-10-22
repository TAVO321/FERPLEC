import Alpine from 'alpinejs';

window.Alpine = Alpine;

const CART_KEY = 'ferplec_cart';

function readCart() {
    try {
        const parsed = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
}

function writeCart(items) {
    try {
        localStorage.setItem(CART_KEY, JSON.stringify(items));
    } catch {
        return;
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('carousel', () => ({
        atStart: true,
        atEnd: false,
        init() {
            this.update();
            this.onScroll = () => this.update();
            this.onResize = () => {
                clearTimeout(this.resizeTimer);
                this.resizeTimer = setTimeout(() => this.update(), 150);
            };
            this.$refs.track.addEventListener('scroll', this.onScroll, { passive: true });
            window.addEventListener('resize', this.onResize, { passive: true });
        },
        destroy() {
            this.$refs.track?.removeEventListener('scroll', this.onScroll);
            window.removeEventListener('resize', this.onResize);
            clearTimeout(this.resizeTimer);
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
        items: readCart(),
        add(product) {
            const stock = Number(product.stock);
            const limited = Number.isFinite(stock);
            if (limited && stock <= 0) return;
            const existing = this.items.find(i => i.code === product.code && i.variant === product.variant);
            if (existing) {
                const max = limited ? stock : Infinity;
                existing.qty = Math.min((existing.qty || 0) + (product.qty || 1), max);
            } else {
                this.items.push({ ...product, qty: product.qty || 1 });
            }
            this.items = [...this.items];
            writeCart(this.items);
        },
        remove(index) {
            this.items.splice(index, 1);
            this.items = [...this.items];
            writeCart(this.items);
        },
        updateQty(index, qty) {
            if (qty <= 0) return this.remove(index);
            this.items[index].qty = qty;
            this.items = [...this.items];
            writeCart(this.items);
        },
        clear() {
            this.items = [];
            writeCart(this.items);
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
