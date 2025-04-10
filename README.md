## REST API

## Setup

1. Clone the repository:
   ```
   git clone https://github.com/isilnieks/bank-project.git
   cd bank-project
   ```

2. Copy environment file:
   ```
   cp .env.example .env
   ```

3. Get API key from https://currencyfreaks.com/ and add to `.env`:
   ```
   CURRENCY_FREAKS_API_KEY=your_api_key_here
   ```

4. Start containers:
   ```
   docker-compose up -d
   ```

5. Install dependencies:
   ```
   docker-compose exec web php bin/console doctrine:migrations:migrate
   ```

6. Run migrations:
   ```
   docker-compose exec web php bin/console doctrine:migrations:migrate
   ```

7. Load sample data (optional):
   ```
   docker-compose exec web php bin/console doctrine:fixtures:load
   ```

## API Endpoints

### Get Accounts by Client ID
```
GET /api/clients/{clientId}/accounts
```

### Get Transaction History
```
GET /api/accounts/{accountId}/transactions?offset=0&limit=10
```

### Transfer Funds
```
POST /api/transactions/transfers
```
Request body:
```json
{
  "from_account_id": 123,
  "to_account_id": 456,
  "amount": 100.00,
  "currency": "USD"
}
```

## Testing

```
docker-compose exec web bin/phpunit
```

## URLs

- Application: http://localhost:8080
- Database: localhost:3306 (user/password)
