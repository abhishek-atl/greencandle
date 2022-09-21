<div class="bootstrap-pagination">
    <nav>
        <ul class="pagination">
            <li class="page-item" :class="{'disabled' : current_page == 1 }">
                <a class="page-link" @click.prevent="paginate(current_page-1)" href="javascript:void(0)" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span> <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    v-if="current_page > 3" :class="{'active' : 1 == current_page }" @click.prevent="paginate(1)">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" v-if="current_page > 4"
                    :class="{'active' : 1==current_page }"  @click.prevent="paginate(1)">...</a>
            </li>
            <template v-for="page in last_page">
                <li class="page-item" v-if="page >= current_page - 2 && page <= current_page + 2" :class="{'active' : page==current_page }">
                    <a href="#" class="page-link" @click.prevent="paginate(page)" v-if="page == current_page">@{{ page }}</a>
                    <a href="#" class="page-link" @click.prevent="paginate(page)" v-else>@{{ page }}</a>
                </li>
            </template>
            <li class="page-item">
                <a href="javascript:void(0)" class="page-link" v-if="current_page < last_page - 3">...</a>
            </li>
            <li class="page-item" :class="{'active' : last_page==current_page }" v-if="current_page < last_page - 2">
                <a href="#" class="page-link" @click.prevent="paginate(last_page)">@{{ last_page }}</a>
            </li>
            <li class="page-item" :class="{ 'disabled': current_page == last_page }">
                <a @click.prevent="paginate(current_page+1)" class="page-link" >
                    <span aria-hidden="true">&raquo;</span> <span class="sr-only">Next</span>
                </a>
            </li>
            <li class="ml-4 mt-2">
                <p class="text-sm">
                Showing
                <span class="font-sm">@{{ from }}</span>
                to
                <span class="font-sm">@{{ to }}</span>
                of
                <span class="font-sm">@{{ total }}</span>
                results
            </p></li>
        </ul>
    </nav>
</div>
