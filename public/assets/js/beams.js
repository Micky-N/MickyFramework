const beamsClient = new PusherPushNotifications.Client({
    instanceId: env.BEAMS_PUBLIC_KEY,
});

const beamsTokenProvider = new PusherPushNotifications.TokenProvider({
    url: "https://mickyframework.loc/pusher/beams-auth",
});

beamsClient.start()
    .then(() => beamsClient.setUserId("ProductModule.Models.User."+auth.id, beamsTokenProvider))
    .catch(console.error);