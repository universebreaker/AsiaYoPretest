## DB test Q1

```
SELECT
    orders.bnb_id AS bnb_id,
    bnbs.name AS bnb_name,
    SUM(orders.amount) AS may_amount
FROM
    orders
LEFT JOIN
    bnbs ON orders.bnb_id = bnbs.id
WHERE
    orders.currency = 'TWD'
    AND orders.created_at BETWEEN '2023-05-01' AND '2023-05-31' 
GROUP BY
    orders.bnb_id
ORDER BY
    may_amount DESC
LIMIT 10;
```

## DB test Q2
可以用 `explain` 檢視query的執行成本分佈，再決定對應的改善手段
如果只針對上述query，比較主要的手段有這些：
1. 對created_at以及currency建立index
2. 將order table的每月各旅宿訂單總金額建立view
3. 使用訂單月分建立partition

## API test Q1

controller:

單一職責(S)
OrderController只負責處理訂單相關request和response
開放封閉(O)
擴充Controller class以建立OrderController class，OrderController亦可被擴充以增加功能
依賴反轉(D)
OrderController依賴OrderCheckAndConvertRequest等其他object class而非特定instance

request:

單一職責(S)
request只定義及檢查請求
開放封閉(O)
擴充FormRequest以建立OrderCheckAndConvertRequest提供所需request定義及檢查
依賴反轉(D)
OrderCheckAndConvertRequest依賴Validator等object class而非特定instance

rules:
單一職責(S)
rules只定義檢證規則
開放封閉(O)
擴充Rules以建立OrderNameRule，可進一步擴充以增加規則
依賴反轉(D)
OrderNameRule依賴ValidationRule等object class而非特定instance


## Command

```
# build and run container
docker build -t laravel-app .
docker run -d -p 8000:8000 --name laravel-app laravel-app
# run UAT
docker exec -it laravel-app ./vendor/bin/pest
# send request
curl --location 'localhost:8000/api/orders' \
--header 'Content-Type: application/json' \
--data '{
    "id": "A0000001",
    "name": "Melody Holiday Inn",
    "address": {
        "city": "taipei-city",
        "district": "da-an-district",
        "street": "fuxing-south-road"
    },
    "price": 200,
    "currency": "TWD"
}'
```
