
# PATCH Behaviour

200 On success
404 When not found
422 When missing required parameters

    PATCH /user/tokens/{id}
    X-API-App-Secret: {API App secret}
    X-API-Key: {API Key}
    {
        "description": "{Description}",
        "enabled": "{Enabled}"
    }

