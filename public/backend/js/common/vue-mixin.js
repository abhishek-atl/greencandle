// define a mixin object
var commonMixin = {
    methods: {
        deleteConfirm(id) {

            let vm = this
            swal({
                    title: "Are you sure to delete?",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete!",
                },
                function() {
                    vm.deleteRecord(id)
                }
            );
        },
        async deleteRecord(id) {

            let routeName = route().current() + '.delete'
            const response = await $httpDelete(route(routeName, { 'id': id }))
            if (response == 1) {
                toastr.success("Record has been deleted successfully!")
                this.fetchData()
            } else
                swal("Something went wrong!", "danger");
        },
        paginate(page) {

            this.current_page = page;
            this.fetchData();
        },
        classSort(column) {
            return {
                'icon-arrow-up': this.order == 'desc' && this.order_by == column,
                'icon-arrow-down': this.order == 'asc' && this.order_by == column,
            }
        },
        sort(order_by) {
            this.order_by = order_by;
            this.order = this.order == 'asc' ? 'desc' : 'asc';
            this.fetchData();
        },
        clearSearch() {
            this.search_text = '';
            this.fetchData();
        },
        search: _.debounce(function() {
            this.fetchData()
        }, 500),
    },
}