import { defineStore } from 'pinia';

export const useNotificationStore = defineStore({
    id: 'notifications',
    state: () => ({
        notifications: []
    }),
    getters: {
        getNotifications: (state) => state.notifications
    },
    actions: {
        notificationAutoRemove() {
            this.invokeRemoveNotification(this.notifications.length - 1);
        },
        invokeAddNotification(notification) {
            this.notifications.push(notification);
            // Action is used because regular function is not State-aware and will not get the updated notifications array count
            setTimeout(this.notificationAutoRemove, 3000);
        },
        invokeRemoveNotification(index) {
            this.notifications.splice(index, 1);
        }
    }
});
