# Stazeni

```bash
git clone https://github.com/1coro/test.git test
```

# Instalace

```bash
cd test
docker-compose up
```

# Testy

## Vlozeni zaznamu:

```bash
curl -H "Content-Type: application/json" \
  --request POST \
  --data '{"method": "insert", "params": {"id_hry": 10, "id_usera": 15, "score": "99"}, "id":1}' \
  http://localhost:8088/api/score
```

## Vypis skore:

```bash
curl -H "Content-Type: application/json" \
  --request POST \
  --data '{"method": "list", "params": {"id_hry": 10}, "id":1}' \
  http://localhost:8088/api/top
```
