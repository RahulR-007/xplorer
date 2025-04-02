// /api/blob-upload.js
import { put } from '@vercel/blob';
import { nanoid } from 'nanoid';

export const config = {
  api: {
    bodyParser: false, // we handle form data manually
  },
};

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).send("Only POST allowed");
  }

  try {
    const busboy = require('busboy');
    const bb = busboy({ headers: req.headers });

    let fileBuffer = Buffer.from([]);
    let fileName = '';

    bb.on('file', (_, file, info) => {
      fileName = `${nanoid()}-${info.filename}`;
      file.on('data', (data) => {
        fileBuffer = Buffer.concat([fileBuffer, data]);
      });
    });

    bb.on('finish', async () => {
      const blob = await put(`uploads/${fileName}`, fileBuffer, {
        access: 'public',
      });

      res.status(200).json({ url: blob.url });
    });

    req.pipe(bb);
  } catch (err) {
    console.error(err);
    res.status(500).send("Upload failed");
  }
}
