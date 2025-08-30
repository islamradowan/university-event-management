<template>
  <div class="bg-white p-4 rounded shadow mb-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="font-semibold">Advanced Search</h3>
      <button @click="showFilters = !showFilters" class="text-sm text-indigo-600">
        {{ showFilters ? 'Hide' : 'Show' }} Filters
      </button>
    </div>
    
    <div class="space-y-4">
      <!-- Search Input -->
      <input 
        v-model="filters.search" 
        @input="emitFilters"
        placeholder="Search events..." 
        class="w-full border rounded px-3 py-2"
      />
      
      <!-- Advanced Filters -->
      <div v-if="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Category Filter -->
        <select v-model="filters.category" @change="emitFilters" class="border rounded px-3 py-2">
          <option value="">All Categories</option>
          <option value="Workshop">Workshop</option>
          <option value="Seminar">Seminar</option>
          <option value="Competition">Competition</option>
          <option value="Festival">Festival</option>
          <option value="Cultural">Cultural</option>
        </select>
        
        <!-- Status Filter -->
        <select v-model="filters.status" @change="emitFilters" class="border rounded px-3 py-2">
          <option value="">All Status</option>
          <option value="approved">Approved</option>
          <option value="pending">Pending</option>
          <option value="rejected">Rejected</option>
        </select>
        
        <!-- Location Filter -->
        <input 
          v-model="filters.location" 
          @input="emitFilters"
          placeholder="Location..." 
          class="border rounded px-3 py-2"
        />
        
        <!-- Date Range -->
        <input 
          v-model="filters.startDate" 
          @change="emitFilters"
          type="date" 
          class="border rounded px-3 py-2"
        />
        <input 
          v-model="filters.endDate" 
          @change="emitFilters"
          type="date" 
          class="border rounded px-3 py-2"
        />
        
        <!-- Capacity Range -->
        <div class="flex gap-2">
          <input 
            v-model.number="filters.minCapacity" 
            @input="emitFilters"
            type="number" 
            placeholder="Min capacity" 
            class="border rounded px-3 py-2 w-1/2"
          />
          <input 
            v-model.number="filters.maxCapacity" 
            @input="emitFilters"
            type="number" 
            placeholder="Max capacity" 
            class="border rounded px-3 py-2 w-1/2"
          />
        </div>
      </div>
      
      <!-- Sort Options -->
      <div class="flex gap-4 items-center">
        <label class="text-sm font-medium">Sort by:</label>
        <select v-model="filters.sortBy" @change="emitFilters" class="border rounded px-3 py-2">
          <option value="start_at">Date</option>
          <option value="title">Title</option>
          <option value="capacity">Capacity</option>
          <option value="created_at">Created</option>
        </select>
        <select v-model="filters.sortOrder" @change="emitFilters" class="border rounded px-3 py-2">
          <option value="asc">Ascending</option>
          <option value="desc">Descending</option>
        </select>
      </div>
      
      <!-- Clear Filters -->
      <button @click="clearFilters" class="text-sm text-gray-600 hover:text-gray-800">
        Clear All Filters
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdvancedSearch',
  data() {
    return {
      showFilters: false,
      filters: {
        search: '',
        category: '',
        status: '',
        location: '',
        startDate: '',
        endDate: '',
        minCapacity: null,
        maxCapacity: null,
        sortBy: 'start_at',
        sortOrder: 'asc'
      }
    }
  },
  methods: {
    emitFilters() {
      this.$emit('filters-changed', { ...this.filters })
    },
    clearFilters() {
      this.filters = {
        search: '',
        category: '',
        status: '',
        location: '',
        startDate: '',
        endDate: '',
        minCapacity: null,
        maxCapacity: null,
        sortBy: 'start_at',
        sortOrder: 'asc'
      }
      this.emitFilters()
    }
  }
}
</script>