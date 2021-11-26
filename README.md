[EonX PHP Developer Test Task](https://www.notion.so/EonX-PHP-Developer-Test-Task-927bc6ebba214cd4a691aeb05056048c)

**Features overview**

- Imports customers from a 3rd party data provider and saves to database by bin/console **app:customers-import** command
- Displays a list of customers from the database by /customers url
- Select and display details of a single customer from the database by /customers/<id> url

**Setup:**
setup your db config or use docker, dont forget to migrate using command doctrine:migrations:migrate

**Config:**
  you can configure customers importer service in **app/config/packages/customer_importer_config.yaml** file <br />
  ```  parameters: <br />
  customer_importer_data_provider: 'https://randomuser.me/api'    - change source here
  request_params:                                                 - customize your parameters here 
    nat: 'au'
    results: '150'
    inc: 'name, email, login, gender, location, phone' ```
