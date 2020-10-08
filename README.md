[![Maintainability](https://api.codeclimate.com/v1/badges/63637abfd3aa9ba8bb70/maintainability)](https://codeclimate.com/github/InfluxOW/Roadmap-App/maintainability)
![Main workflow](https://github.com/InfluxOW/Roadmap-App/workflows/Main%20workflow/badge.svg)
[![codecov](https://codecov.io/gh/InfluxOW/Roadmap-App/branch/master/graph/badge.svg)](https://codecov.io/gh/InfluxOW/Roadmap-App)

# Roadmap App
http://influx-roadmap.herokuapp.com/api/docs \
This is an API for application that should optimize studying process of developers.
You can check our presentation here (slides 75-86): https://docs.google.com/presentation/d/1q6Z7t6xd7oZY0HvXyqnxEproRlR2kvl5GcKFckRwAGc/edit?pli=1#slide=id.g9663f13cad_2_127

## Requirements
You can check PHP dependencies with the `composer check-platform-reqs` command.

* PHP ^7.4
* Extensions:
    * mbstring
    * curl
    * dom
    * xml
    * zip
    * sqlite
    * json
    
## Development Setup
1. Run `make setup` to setup the project.
2. Run `make seed` to seed the database with fake data.
3. Run `make test` to run tests.
