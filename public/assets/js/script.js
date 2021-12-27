const applicationServerKey = env.VAPID_PUBLIC_KEY;
let serviceWorkerRegistration = null;
let isPushSubscribed = false
window.addEventListener('load', () => {
    if (!('serviceWorker' in navigator)) {
        return;
    }
    if (!('PushManager' in window)) {
        return;
    }
    navigator.serviceWorker.register('https://mickyframework.loc/public/assets/js/sw.js')
        .then((registration) => {
            serviceWorkerRegistration = registration
            initializePushMessage()
            if (!isPushSubscribed) {
                getNotificationPermission().then((status) => {
                    if (status === 'granted') {
                        subscribeUserToPush()
                    }
                })
            }
        })
})

function initializePushMessage() {
    serviceWorkerRegistration.pushManager.getSubscription()
        .then((subscription) => {
            isPushSubscribed = !(subscription === null)
        })
}

function getNotificationPermission() {
    return new Promise((resolve, reject) => {
        if (!("Notification" in window)) {
            reject('support');
        } else {
            Notification.requestPermission((permission) => {
                (permission === 'granted') ? resolve(permission) : reject(permission)
            })
        }
    })
}

function subscribeUserToPush() {
    const subscribeOptions = {
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(applicationServerKey)
    }
    return new Promise((resolve, reject) => {
        serviceWorkerRegistration.pushManager.subscribe(subscribeOptions)
            .then((subscription) => {
                updateSubscriptionOnServer(subscription)
                    .then((status) => {
                        isPushSubscribed = true
                        resolve(status)
                    })
            })
            .catch((error) => {
                reject(error)
            })
    })
}

function updateSubscriptionOnServer(subscription = null, subscribe = true) {
    return new Promise((resolve, reject) => {
        let extra = (subscribe) ? '/subscribe' : '/unsubscribe';
        axios.post('https://mickyframework.loc/subscription' + extra, subscription, {
            headers: {
                'Content-Type': 'application/json',
            },
        }).then((response) => {
            if (!response.data.status || response.data.status !== 'ok') {
                reject(response.status)
            }
            resolve(response.status)
        }).catch((error) => {
            reject(error)
        });
    })
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/')

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length)

    for (let i = 0; i < rawData.length; i++) {
        outputArray[i] = rawData.charCodeAt(i)
    }
    return outputArray;
}