# Define an argument with a default value
ARG source_container=""
FROM $source_container

ARG db_host=""

ARG app_url=""
ARG app_key=""

ARG db_host=""
ARG db_port=""
ARG db_username="promplanner"
ARG db_password="prom"

ARG aws_access_key_id=""
ARG aws_secret_access_key=""
ARG aws_default_region=""
ARG aws_bucket=""
ARG aws_use_path_style_endpoint=""

ARG $mail_username=""
ARG $mail_password=""
ARG $paypal_sandbox_client_id=""
ARG $paypal_sandbox_client_secret=""

# Set important variables
ENV APP_URL=$app_url
ENV APP_KEY=$app_key

ENV DB_HOST=$db_host
ENV DB_USERNAME=$db_username
ENV DB_PASSWORD=$db_password

ENV AWS_ACCESS_KEY_ID=$aws_access_key_id
ENV AWS_SECRET_ACCESS_KEY=$aws_secret_access_key
ENV AWS_DEFAULT_REGION=$aws_default_region
ENV AWS_BUCKET=$aws_bucket
ENV AWS_USE_PATH_STYLE_ENDPOINT=$aws_use_path_style_endpoint

ENV MAIL_USERNAME=$mail_username
ENV MAIL_PASSWORD=$mail_password
ENV PAYPAL_SANDBOX_CLIENT_ID=$paypal_sandbox_client_id
ENV PAYPAL_SANDBOX_CLIENT_SECRET=$paypal_sandbox_client_secret

ARG source_folder="super_admin"
# Command to copy a specific portal's contents
COPY /$source_folder /var/www/html
CMD php artisan serve --host 0.0.0.0 --port=80
