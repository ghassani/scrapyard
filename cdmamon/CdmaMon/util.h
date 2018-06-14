/* -*- Mode: C; tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 * Copyright (C) 2010 Red Hat, Inc.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of version 2 of the GNU General Public
 * License as published by the Free Software Foundation
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

#pragma once

#include <stdint.h> 
#include <iostream>

uint16_t crc16 (const char *buffer, size_t len);

size_t dm_escape (const char *inbuf,
                 size_t inbuf_len,
                 char *outbuf,
                 size_t outbuf_len);

size_t dm_unescape (const char *inbuf,
                   size_t inbuf_len,
                   char *outbuf,
                   size_t outbuf_len,
                   bool *escaping);

size_t dm_encapsulate_buffer (char *inbuf,
                             size_t cmd_len,
                             size_t inbuf_len,
                             char *outbuf,
                             size_t outbuf_len);

bool dm_decapsulate_buffer (const char *inbuf,
                                size_t inbuf_len,
                                char *outbuf,
                                size_t outbuf_len,
                                size_t *out_decap_len,
                                size_t *out_used,
                                bool *out_need_more);

std::string bytesToHex(char* bytes, int size);
std::string bitToBinary(int in);