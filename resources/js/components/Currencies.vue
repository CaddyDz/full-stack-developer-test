<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Currencies</div>
          <div class="card-body">
            <div class="dropdown" v-if="currencies">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Base Currency
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" v-for="currency in currencies" :key="currency.id"
                        @click="changeBaseCurrency(currency.id)">
                        {{ currency.name }}
                    </button>
                </div>
            </div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Average rate</th>
                  <th>Maximum rate</th>
                  <th>Minimum rate</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ average_rate }}</td>
                  <td> {{ maximum_rate }}</td>
                  <td>{{ minimum_rate }}</td>
                </tr>
              </tbody>
            </table>
            <table v-if="currencies" class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Nominal</th>
                  <th>Rate</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="currency in currencies" :key="currency.id">
                  <td>
                    {{ currency.name }}
                    <a :href="`/history/${currency.id}`">View history</a>
                  </td>
                  <td>{{ currency.nominal }}</td>
                  <td>{{ currency.rate }}</td>
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
    currencies: null,
    average_rate: null,
    maximum_rate: null,
    minimum_rate: null
  }),

  methods: {
      changeBaseCurrency(currency) {
          axios.get(`/api/currency?base_currency_id=${currency}`)
            .then((response) => {
                this.currencies = response.data.currencies;
            });
      }
  },

  async mounted() {
    console.log('Component mounted.')

    const { data: {
        currencies,
        average_rate,
        maximum_rate,
        minimum_rate
    } } = await axios.get('/api/currency')

    this.currencies = currencies
    this.average_rate = average_rate
    this.minimum_rate = minimum_rate
    this.maximum_rate = maximum_rate
  },
}
</script>
