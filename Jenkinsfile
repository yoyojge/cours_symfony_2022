pipeline {
  agent any
  stages {
    stage('build') {
      steps {
        bat 'composer install'
      }
    }

    stage('test') {
      parallel {
        stage('test unitaire') {
          steps {
            bat 'php bin/phpunit tests/unit'
          }
        }

        stage('test integration') {
          steps {
            bat 'php bin/phpunit tests/integration'
          }
        }

        stage('test fonctionnel') {
          steps {
            bat 'php bin/phpunit tests/functional'
          }
        }

      }
    }

    stage('deploy') {
      steps {
        bat 'symfony server:start'
      }
    }

  }
}