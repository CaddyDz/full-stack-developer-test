<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Currencies</div>
          <div class="card-body">
            <table v-if="currencies" class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Nominal</th>
                  <th>Rate</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="currency in currencies">
                  <td>{{ currency.name }}</td>
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
  }),

  async mounted() {
    console.log('Component mounted.')

    const { data: { data } } = await axios.get('/api/currency')

    this.currencies = data
  },
}
</script>
