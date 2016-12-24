app.notifications = [];
app.loadNotifications = function (url, notificationUrl, toastrLunch) {
    $.ajax({
        url: url,
        success: function (data, textStatus, jqXHR) {
            var countUnviewed = 0;
            app.notifications = data['_embedded']['items'];

            var count = app.notifications.length;

            var notificationBar = $('#header_notification_bar');
            var notificationsList = notificationBar.find('.all-notifications');
            notificationsList.empty();
            moment.lang('es');
            for (var i = 0; i < count; i++) {
                if (app.notifications[i].viewed == false) {
                    countUnviewed++;
                    if (toastrLunch) {
                        toastr.success(app.notifications[i].message, 'Importante', {
                            notification: app.notifications[i],
                            url: this.url,
                            onclick: function () {
                                var url = this.url + '?view_notification=' + this.notification.id;
                                app.loadNotifications(url, notificationUrl);
                            }
                        });
                    }

                }
                notificationsList.append('<li><a href="' + notificationUrl + '?view_notification=' + app.notifications[i].id + '"><div class="time">' + moment(app.notifications[i].created_at).fromNow() + '</div><div class="details' + (app.notifications[i].viewed ? '' : ' bold') + '">' + app.notifications[i].message.replace(/<.*>(.*?)<\/.*>/g, '$1') + ' </div></a></li>');
            }
            notificationBar.find('.no_view-notifications').text(countUnviewed);
            notificationBar.find('.count-notifications').text(count);
        }
    });
    setTimeout(function () {
        toastr.clear();
        app.loadNotifications(url, notificationUrl, toastrLunch);
    }, 300000); // do each 60 seconds
};