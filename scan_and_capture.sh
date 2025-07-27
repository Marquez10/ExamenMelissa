#!/bin/bash
# Script de reconocimiento: nmap + captura de red con tshark
# Uso: ./scan_and_capture.sh <objetivo> <duración_en_segundos>

if [ $# -lt 2 ]; then
  echo "Uso: $0 <objetivo> <duración_en_segundos>"
  exit 1
fi

TARGET=$1
DURATION=$2

echo "==> Ejecutando nmap scan contra $TARGET..."
nmap -sS -sV -oN nmap_results.txt "$TARGET"

echo "==> Iniciando captura de red durante $DURATION segundos (tshark)..."
timeout $DURATION tshark -i any -w capture.pcap

echo "==> Proceso completado."
echo "Archivos generados:"
echo "  • nmap_results.txt  (resultados de nmap)"
echo "  • capture.pcap      (traza de red para analizar en Wireshark)"
echo ""
echo "Sugerencia de filtros en Wireshark:"
echo "  • tcp.port == 80    (HTTP)"
echo "  • http              (protocol HTTP)"
echo "  • dns               (consultas DNS)"
echo "  • tcp.port == 22    (SSH)"
echo ""
echo "Abre capture.pcap en Wireshark y aplica filtros para describir el tráfico observado."
