***APL System REST***

Works on at least PHP v7.2.9


**Setup**
- Clone repository
    - If you want Swagger UI enabled, project directory needs the url: http://localhost/apl-system-rest/. Alternatively add the server url to the servers node in the file /tools/swagger-ui/openapi.yaml
- Initialize and import database (db.sql)
- Update necessary fields inside config.ini


**Getting started**
- Define a period by adding a row to table *access_period*
- Add a company to table *company*
- Add students and define a relation to *access_period* and *company*

Service should now work. Navigate to /tools/swagger-ui to read documentation or to try it out
