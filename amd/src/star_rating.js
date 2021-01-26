define(['jquery', 'core/ajax', 'core/notification', 'core/templates'], function($, Ajax, Notification, Templates) {

    let StarRating = function(rootSelector, contextid) {
        this.root = $(rootSelector);
        this.contextid = contextid;
        this.options = this.root.data('options');
        console.log(this.options);

        this.root.on('click', '[data-action=set-rating]', (e) => {
            this.setRating($(e.currentTarget).data('value')).done((response) => {
                let stars = [];
                let starrating = response.rating.rating * this.options.scale;
                for (let i = 1; i <= this.options.numberofstars; i++) {
                    stars.push({
                        fullstar: i <= starrating,
                        halfstar: i > starrating && i < (starrating + 1),
                        rating: i / this.options.scale
                    });
                }

                Templates.render('filter_rating/stars', {stars: stars, myrating: true}).then((html, js) => {
                    this.root.html(html);
                }).fail(Notification.exception);
            });
        });
    };

    StarRating.prototype.refresh = function() {
        this.getAverageRating().done((averageRating) => {
            const averageStars = averageRating * this.options.scale;
            let stars = [];
            for (let i = 1; i <= this.options.numberofstars; i++) {
                stars.push({
                    fullstar: i <= averageStars,
                    halfstar: i > averageStars && i < (averageStars + 1),
                    rating: i / this.options.scale
                });
            }

            Templates.render('filter_rating/stars', {stars: stars}).then((html, js) => {
                this.root.html(html);
            }).fail(Notification.exception);
        }).fail(Notification.exception);
    };

    /**
     * Get average rating for this context.
     *
     * @returns {Promise}
     */
    StarRating.prototype.getAverageRating = function() {
        return Ajax.call([{
            methodname: 'filter_rating_get_average_rating',
            args: {contextid: this.contextid}
        }])[0];
    };

    /**
     * Get average rating for this context.
     *
     * @param {int} rating
     * @returns {Promise}
     */
    StarRating.prototype.setRating = function(rating) {
        return Ajax.call([{
            methodname: 'filter_rating_set_rating',
            args: {
                contextid: this.contextid,
                rating: rating
            }
        }])[0];
    };

    return StarRating;
});