<template>
  <div class="px-4 py-8">
    <form @submit.prevent>
      <input
        type="text"
        v-model="filters.query"
        class="block w-full border rounded-full py-2 px-4 text-lg text-gray-800 focus:border-blue-700 focus:outline-none"
        placeholder="Search in the job listings..."
        @input="onSearchInputChange"
      >
    </form>

    <div v-if="error !== null" class="mt-8 py-3 px-4 bg-red-200 rounded space-y-1">
      <p class="text-red-800 font-medium">{{ error.title }}</p>
      <p class="text-gray-700 text-sm">{{ error.details }}</p>
    </div>

    <table
      v-infinite-scroll="loadMore"
      infinite-scroll-disabled="busy"
      infinite-scroll-distance="10"
      class="w-full mt-8"
    >
      <thead>
        <tr>
          <th class="pr-1 pl-2 pb-2 text-left text-xs whitespace-no-wrap uppercase font-bold text-gray-600">
            <button type="button" @click="sortBy('title')">
              Title
            </button>
          </th>
          <th class="px-1 pb-2 text-left text-xs whitespace-no-wrap uppercase font-bold text-gray-600">
            <button type="button" @click="sortBy('company_name')">
              Company
            </button>
          </th>
          <th class="pr-2 pl-1 pb-2 text-left text-xs whitespace-no-wrap uppercase font-bold text-gray-600">
            <button type="button" @click="sortBy('published_at')">
              Publish Date
            </button>
          </th>
        </tr>
      </thead>

      <tbody v-if="!isLoading">
        <tr
          v-for="listing in listings.data"
          :key="listing.id"
          class="hover:bg-white focus:bg-white focus:outline-none transition duration-150"
          tabindex="0"
        >
          <td class="pr-1 pl-2 py-2">
            <p class="text-blue-800">#{{ listing.id }}, {{ listing.title }}</p>
            <p class="text-sm text-gray-500">{{ listing.description|trim(100) }}</p>
          </td>

          <td class="px-1 py-2 text-sm text-gray-500">
            {{ listing.company.name }}
          </td>

          <td class="pr-2 pl-1 py-2 text-sm whitespace-no-wrap text-gray-600">
            {{ listing.published_at | date('dd/MM/yyyy') }}
          </td>
        </tr>
      </tbody>

      <tfoot v-else>
        <tr>
          <td colspan="3">
            <p class="py-4 text-center text-gray-600 text-sm italic">
              Let me just grab the latest and greatest job listings for you. I'll be just a second!
            </p>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</template>

<script>
import axios from 'axios';
import { debounce } from 'throttle-debounce';

export default {
  data: () => ({
    isLoading: true,
    page: 1,
    filters: {
      query: '',
      sort: null,
    },
    listings: {
      data: [],
      total: 0,
    },
    error: null,
  }),

  created() {
    // This makes sure that client/user won't be triggering search requests
    // until they finish typing.
    this.fetch = debounce(500, this.fetch.bind(this));
  },

  async mounted() {
    // Fetch the listing data once the component is ready.
    this.fetch();
  },

  methods: {
    async makeFetchRequest(page = 1) {
      return axios.get('/api/listings', {
        params: {
          search: this.filters.query,
          sort: this.filters.sort,
          page: Number.isFinite(page) ? page : 1,
        },
      });
    },

    async fetch() {
      this.error = null;
      this.isLoading = true;

      try {
        const response = await this.makeFetchRequest();
        this.listings = response.data.listings;
      } catch (e) {
        if (Object.prototype.hasOwnProperty(e, 'response')) {
          this.error = {
            title: e.response.data.error,
            details: 'Sorry for the inconvenience. We will be looking into it right away.',
          };
        } else {
          this.error = {
            title: 'Yikes. Our servers are acting funky.',
            details: e.message,
          };
        }
      } finally {
        this.isLoading = false;
      }
    },

    sortBy(column) {
      if (this.filters.sort !== null) {
        const isSortingByTheSameColumn = this.filters.sort.search(column) > -1;

        // If the client is already sorting by the selected column...
        if (isSortingByTheSameColumn > -1) {
          // Invert the order of the sorting between ascending and descending.
          column = this.filters.sort[0] === '-' ? column : `-${column}`;
        }
      }

      this.filters.sort = column;
      this.fetch();
    },

    async loadMore() {
      if (this.isLoading) {
        return;
      }

      try {
        const response = await this.makeFetchRequest(this.listings.current_page + 1);
        this.listings = {
          ...response.data.listings,
          data: [
            ...this.listings.data,
            ...response.data.listings.data,
          ],
        };
      } catch (e) {
        this.error = {
          title: 'Yikes. Our servers are acting funky.',
          details: e.message,
        };
      }
    },

    onSearchInputChange() {
      this.fetch();
    },
  },
};
</script>
