Wymagane są lokalnie (na host):
* php 8.2
* composer

Żeby w pełni zainicjować projekt wystarczy wywołać `make init`, który instaluje zależności, uruchamia statyczną analizę i testy, oraz podnosi php dev serwer w kontenerze dockerowym.

### Uwagi względem wymagań
- błędy walidacji request są zwracane jako `400 Bad Request`, ale błędy domenowe są zwracane jako `409 Conflict`
- endpoint do tworzenia zamówień to `POST /orders`, nie `POST /order`, który nie jest zgodny z RESTful
- endpoint do tworzenia klienta to `POST /clients` (nie był wymieniony w wymaganich)
- pola `weight`, `price` z kontraktu zamówienia i `balance` z kontraktu klienta mają dodatkowe reguły:
  * mogą zawierać wartość typu float, lub int
  * wartości typu float muszą być podane z dokładnością do dwóch miejsc po przecinku
- lista produktów zamówienia nie jest zapisywana w bazie (nie ma wymagania, które by tego wymagało), jest dostępna w evencie `OrderCreated`
- client name nie jest zapisywany w bazie (nie ma wymagania, które by tego wymagało), jest dostępny w evencie `ClientCreated`

### Dostępne akcje

#### Utworzenie nowego klienta
```http request
POST http://localhost:44444/clients
Content-Type: application/json

{
  "clientId": "9846e81a-7067-437d-96aa-9ea523512e0c",
  "name": "TEST name",
  "balance": 100
}
```

#### Utworzenie nowego zamówienia
```http request
POST http://localhost:44444/orders
Content-Type: application/json

{
  "orderId": "7e37bcf9-ba39-4c4e-af56-6e262bc726a6",
  "clientId": "9846e81a-7067-437d-96aa-9ea523512e0c",
  "products": [
    {
      "productId": "A_ID_1",
      "quantity": 2,
      "weight": 12,
      "price": 10
    },
    {
      "productId": "B_ID_2",
      "quantity": 3,
      "weight": 13.31,
      "price": 12.12
    }
  ]
}
```

#### Podgląd audit log
```shell
bin/console app:audit-log
```

#### Zasymulowanie zewnętrznego eventu "client-blocked"
```shell
bin/console app:queue:block-client 9846e81a-7067-437d-96aa-9ea523512e0c
```

#### Zasymulowanie zewnętrznego eventu "client-topped-up"
```shell
bin/console app:queue:top-up-client 9846e81a-7067-437d-96aa-9ea523512e0c 100.0
```
