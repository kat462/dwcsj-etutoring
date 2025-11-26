// Real-time notifications with Laravel Echo and Pusher
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
	broadcaster: 'pusher',
	key: import.meta.env.VITE_PUSHER_APP_KEY,
	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
	forceTLS: true
});

if (window.Laravel && window.Laravel.userId) {
	window.Echo.private('App.Models.User.' + window.Laravel.userId)
		.notification((notification) => {
			// Optionally, update the notification bell and dropdown in real-time
			if (window.updateNotificationsDropdown) {
				window.updateNotificationsDropdown(notification);
			}
		});
}
import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Sidebar toggle behavior (mobile)
import './sidebar';
