tailwind.config = {
        theme: {
        extend: {
            fontFamily: {
            jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            colors: {
            maroon: {
                DEFAULT: '#800000',
            },
            },
        },
        },
    };

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('dropdownButton');
    const menu = document.getElementById('dropdownMenu');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }
});
