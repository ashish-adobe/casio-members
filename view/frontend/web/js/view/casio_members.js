/**
 * Copyright © CO-WELL ASIA CO.,LTD.
 * See COPYING.txt for license details.
 */
define([
    'ko',
    'Casio_Core/js/view/load_more',
    'jquery',
    'mage/translate'
], function (ko, Component, $, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Casio_CasioMembers/casio_members_product',
            dataName: ['products'],
            contentType: 'application/json',

        },
        productList: ko.observableArray([]),
        totalResults: ko.observable(0),
        isEnableShowMoreProductButton: ko.observable(true),
        casioMemberSubStatus: ko.observable(false),
        productRegistration: ko.observable($t('Product registration')),
        filterOptions: ko.observableArray([]),
        chosenFilter: ko.observableArray([0]),
        offsetValue: 0,
        isLoadAll: false,

        initialize: function () {
            var self = this;
            self._super();
            this.ajaxData = {
                customerId: self.customerId,
                selectLang: self.selectLang,
                limit: self.defaultShow,
                offset: self.offsetValue,
                order: self.chosenFilter()[0]
            };
            self.loadMore(self.defaultShow, self.ajaxData);

            self.dataList.subscribe(function () {
                if(self.responseData.hasOwnProperty('totalResults')) {
                    self.totalResults(self.responseData.totalResults);
                }

                if(self.responseData.hasOwnProperty('sub_status') && self.responseData['sub_status'] === 'OK'){
                    self.casioMemberSubStatus(true);
                }

                if(self.responseData.hasOwnProperty('totalResults') && self.responseData['totalResults'] <= self.dataList().length){
                    self.isEnableShowMoreProductButton(false);
                }
                self.productList(self.dataList());
            });
            self.addFilterOptions();
        },

        /**
         * Init observable variables
         *
         * @returns {Element}
         */
        initObservable: function () {
            var self = this;
            this._super();
            this.countRequest.subscribe(function (value) {
                this.ajaxData.offset = self.defaultShow * value;
            }.bind(self));

            this.chosenFilter.subscribe(function (value) {
                this.ajaxData.order = value[0];
            }.bind(self));

            this.isEnableShowMoreProductButton.subscribe(function (value) {
                this.isLoadAll = true;
            }.bind(self));

            return this;
        },

        /**
         * Add default filter options
         */
        addFilterOptions: function() {
            this.filterOptions([
                {
                    'label': $t('Newest registration date'),
                    'value': 0
                },
                {
                    'label': $t('Oldest registration date'),
                    'value': 1
                },
                {
                    'label': $t('Alphabet of the product name'),
                    'value': 2
                },
                {
                    'label': $t('Newest purchase date'),
                    'value': 3
                },
                {
                    'label': $t('Oldest purchase date'),
                    'value': 4
                }
            ]);
        },

        /**
         * Trigger when change filter order
         */
        changeFilterOrder: function() {
            var self = this;
            self.resetParams();
            self.loadMore(parseInt(self.defaultShow), self.ajaxData);
        },

        /**
         * Get product to show in frontend
         */
        getProduct: function () {
            var self = this;
            if (self.isLoadAll) {
                self.productList(self.dataStorage());
                self.isEnableShowMoreProductButton(false);
            } else {
                this.loadMore(parseInt(this.defaultShow), this.ajaxData);
            }
        },

        /**
         * scroll to element has id is is_registration
         */
        scrollToIsRegistration: function () {
            if(window.location.href.search('#is-registration') !== -1) {
                $('html, body').animate({
                    scrollTop: $("#is-registration").offset().top
                }, 2000);
            }
        },

        /**
         * Reset params request
         */
        resetParams: function () {
            var self = this;
            self.isEnableShowMoreProductButton(true);
            self.countRequest(0);
            self.productList([]);
            self.dataList([]);
            self.dataStorage([]);
            self.isLoadAll = false;
        },

        /**
         * Hidden product
         */
        hiddenProduct() {
            var self = this,
                productList = self.productList.slice(0, self.defaultShow);
            self.productList(productList);
            self.isEnableShowMoreProductButton(true);
        },
    });
});
