# Wigilabs Backend Proxy

## Backend Proxy para Servicios Legacy

Backend proxy que actúa como intermediario entre aplicaciones cliente y servicios legacy (SOAP/REST), implementando caching y logging. Desplegado en RedHat OpenShift.

## Características Principales
- **Integración con servicios SOAP/REST**: 4 endpoints (2 en CodeIgniter, 2 en Slim).
- **Patrón Decorator**: Para caching y logging transparente.
- **Caching con Redis**: TTL configurable por entorno.
- **Logging estructurado**: Con Monolog y adaptador personalizado.
- **Pruebas unitarias**: >90% de cobertura con PHPUnit 9.x.

## Requisitos
- PHP 7.4+
- Composer 2.x
- Redis 6.x
- OpenShift 4.x (para despliegue)
- MySQL 5.7+ (si se usa base de datos)

## Instalación
```bash
git clone git@github.com:jucarozu/wigilabs-backend-proxy.git
cd wigilabs-backend-proxy
composer install
```

## Despliegue en OpenShift
1. Construir imagen: `docker build -t wigilabs-backend-proxy .`
2. Crear secret para credenciales: `oc create secret generic db-creds --from-literal=DB_PASSWORD=...`
3. Desplegar: `oc new-app wigilabs-backend-proxy`
