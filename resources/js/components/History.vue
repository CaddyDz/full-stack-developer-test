<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Historic rates</div>
          <div class="card-body">
            <table v-if="rates" class="table table-bordered">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Rate</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="rate in rates" :key="rate.id">
                  <td>
                    {{ rate.name }}
                    <a :href="`/history/${rate.id}`">View history</a>
                  </td>
                  <td>{{ rate.nominal }}</td>
                  <td>{{ rate.rate }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

  data: () => ({
    rates: null,
    average_rate: null,
    maximum_rate: null,
    minimum_rate: null
  }),

  async mounted() {
    console.log('Component mounted.')

    const { data: {
        rates,
        average_rate,
        maximum_rate,
        minimum_rate
    } } = await axios.get('/api/rate')

    this.rates = rates
    this.average_rate = average_rate
    this.minimum_rate = minimum_rate
    this.maximum_rate = maximum_rate
  },
}
</script>
