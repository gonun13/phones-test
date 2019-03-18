# phones-test
Quick app
(Fresh Ubuntu 18 and only requires docker and docker compose)

Steps to install (requires sudo level)

```
git clone git@github.com:gonun13/phones-test.git

cd phones-test

./start
```

Running start will: 
Create two containers (PHP & Web with all required dependencies) & boot them up.

You can access the dataset on: <your_ip>:8080

## How to test

A number of Curl calls are available for testing all features.

`./curls/curl_getAll`
Json dataset with all records

`./curls/curl_filterCountry`
Json dataset with all records for Uganda

`./curls/curl_filterState`
Json dataset with all OK records

`./curls/curl_filterPage`
Json dataset paginated showing page 2

`./curls/curl_filterAll`
Json dataset applying all filters (Cameroon, NOK, page 1)
