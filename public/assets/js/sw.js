self.addEventListener('install', (event) => {
    self.skipWaiting();
})

self.addEventListener('push', (event) => {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }
    const options = event.data.json()
    event.waitUntil(self.registration.showNotification(options.title, options))
})

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    switch (event.action) {
        case 'open':
            clients.openWindow(event.notification.data.link);
            break;
        case 'markAsRead':
            event.notification.close();
            break;
    }
})